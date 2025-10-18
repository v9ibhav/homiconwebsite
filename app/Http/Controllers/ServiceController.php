<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\User;
use App\Models\Setting;
use App\Http\Requests\ServiceRequest;
use App\Models\ServicePackage;
use App\Mail\ServiceStatusUpdated;
use Illuminate\Support\Facades\Mail;
use Yajra\DataTables\DataTables;
use App\Models\UserFavouriteService;
use App\Traits\TranslationTrait;
use App\Models\Notification;
use App\Traits\NotificationTrait;


class ServiceController extends Controller
{
    use NotificationTrait,TranslationTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $servicepackage = $request->packageid;
        $postrequestid = $request->route('postjobid');

        $auth_user = auth()->user();

        if ($request->is('servicepackage/list/*')) { 
            if ($auth_user->hasRole('provider')) {
                $isAuthorizedPackage = ServicePackage::where('id', $servicepackage)
                    ->where('provider_id', $auth_user->id)
                    ->exists();
    
                if (!$isAuthorizedPackage) {
                    return redirect()->back()->withErrors(__('You are not authorized to view this package.'));
                }
            }
        }
        $filter = [
            'status' => $request->status,
        ];
        $pageTitle = __('messages.all_form_title',['form' => __('messages.services')] );
        $assets = ['datatable'];
        return view('service.index', compact('pageTitle','auth_user','assets','filter','postrequestid','servicepackage'));
    }  

    // get datatable data
    public function index_data(DataTables $datatable,Request $request)
    {
        $query = Service::query()->where('service_request_status', 'approve')->myService();
        $primary_locale = app()->getLocale() ?? 'en';


        $filter = $request->filter;

        if (isset($filter)) {
            if (isset($filter['column_status'])) {
                $query->where('status', $filter['column_status']);
            }
        }
        if (auth()->user()->hasAnyRole(['admin','provider'])) {
            $query = $query->where('service_type','service')->withTrashed();

        }
        if ($request->has('postrequestid')) {
            $postRequestId = $request->postrequestid;
            $query = Service::whereHas('postJobService', function ($query) use ($postRequestId) {
                $query->where('post_request_id', $postRequestId);
            });
        }
        if ($request->has('servicepackage')) {
            $servicepackage = $request->servicepackage;
            $query = Service::whereHas('servicePackage', function ($query) use ($servicepackage) {
                $query->where('service_package_id', $servicepackage);
            });
        }
        
        return $datatable->eloquent($query)
            ->addColumn('check', function ($row) {

                return '<input type="checkbox" class="form-check-input select-table-row"  id="datatable-row-'.$row->id.'"  name="datatable_ids[]" value="'.$row->id.'" data-type="service" onclick="dataTableRowCheck('.$row->id.',this)">';
            })

            ->editColumn('name', function($query) use($primary_locale){
                $name = $this->getTranslation($query->translations, $primary_locale, 'name', $query->name) ?? $query->name;

                if (auth()->user()->can('service edit')) {
                    return '<a class="btn-link btn-link-hover" href="' . route('service.create', ['id' => $query->id]) . '">' . $name . '</a>';
                }

                return $name ?? '-';
               
            })
            ->filterColumn('name',function($query,$keyword) use($primary_locale){
                if ($primary_locale !== 'en') {
                    $query->where(function ($query) use ($keyword, $primary_locale) {
                        $query->whereHas('translations', function($query) use ($keyword, $primary_locale) {
                                // Search in the translations table based on the primary_locale
                                $query->where('locale', $primary_locale)
                                      ->where('value', 'LIKE', '%'.$keyword.'%');
                            })
                            ->orWhere('name', 'LIKE', '%'.$keyword.'%'); // Fallback to 'name' field if no translation is found
                    });
                } else {
                    $query->where('name', 'LIKE', '%'.$keyword.'%');
                }
               
            })
            ->editColumn('category_id' , function ($query) use($primary_locale){
                $catname = $this->getTranslation(optional($query->category)->translations, $primary_locale, 'name', optional($query->category)->name) ?? optional($query->category)->name;
                
                return $catname ?? '-';
                //return ($query->category_id != null && isset($query->category)) ? $query->category->name : '-';
            })
            ->filterColumn('category_id',function($query,$keyword) use($primary_locale){
                // $query->whereHas('category',function ($q) use($keyword){
                //     $q->where('name','like','%'.$keyword.'%');
                // });
                $query->whereHas('category', function ($q) use ($keyword, $primary_locale) {
                    // Check if the locale is not 'en'
                    if ($primary_locale !== 'en') {
                        $q->where(function ($q) use ($keyword, $primary_locale) {
                            // Search in the translations table for the given locale
                            $q->whereHas('translations', function ($q) use ($keyword, $primary_locale) {
                                $q->where('locale', $primary_locale)
                                  ->where('value', 'LIKE', '%' . $keyword . '%');
                            })
                            // Fallback to checking 'name' field if no translation is found
                            ->orWhere('name', 'LIKE', '%' . $keyword . '%');
                        });
                    } else {
                        // If locale is 'en', search directly in the 'name' field
                        $q->where('name', 'LIKE', '%' . $keyword . '%');
                    }
                });
            })
            ->orderColumn('category_id', function ($query, $order) {
                $query->join('categories', 'categories.id', '=', 'services.category_id')
                      ->orderBy('categories.name', $order);
            })
            ->editColumn('provider_id' , function ($query){
                return view('service.service', compact('query'));
            })
            ->filterColumn('provider_id',function($query,$keyword){
                $query->whereHas('providers',function ($q) use($keyword){
                    $q->where('display_name','like','%'.$keyword.'%');
                });
            })
            ->orderColumn('provider_id', function ($query, $order) {
                $query->select('services.*')
                      ->join('users as providers', 'providers.id', '=', 'services.provider_id')
                      ->orderBy('providers.display_name', $order);   
            })
            ->editColumn('price' , function ($query){
                return getPriceFormat($query->price).'-'.ucFirst($query->type);
            })

            ->editColumn('discount' , function ($query){
                return $query->discount ? $query->discount .'%' : '-';
            })
            ->addColumn('action', function ($data) {
                return view('service.action', compact('data'));
            })
            ->editColumn('status' , function ($query){
                $disabled = $query->trashed() ? 'disabled': '';
                return '<div class="custom-control custom-switch custom-switch-text custom-switch-color custom-control-inline">
                    <div class="custom-switch-inner">
                        <input type="checkbox" class="custom-control-input  change_status" data-type="service_status" '.($query->status ? "checked" : "").'  '.$disabled.' value="'.$query->id.'" id="'.$query->id.'" data-id="'.$query->id.'">
                        <label class="custom-control-label" for="'.$query->id.'" data-on-label="" data-off-label=""></label>
                    </div>
                </div>';
            })

            ->rawColumns(['action', 'status', 'check','name'])
            ->toJson();
    }

    public function request_index_data(DataTables $datatable, Request $request)
    {
        $query = Service::query()->where('is_service_request', 1)->myService();

        $filter = $request->filter;
    
        if (isset($filter)) {
            if (isset($filter['column_status'])) {
                if ($filter['column_status'] === 'pending') {
                    $query->where('service_request_status', 'pending');
                } elseif ($filter['column_status'] === 'reject') {
                    $query->where('service_request_status', 'reject');
                }
                elseif ($filter['column_status'] === 'approve') {
                    $query->where('service_request_status', 'approve');
                }
            }
        }
        if (auth()->user()->hasAnyRole(['admin', 'provider'])) {
            $query = $query->where('service_type', 'service')->withTrashed();
        }
        if ($request->has('postrequestid')) {
            $postRequestId = $request->postrequestid;
            $query = Service::whereHas('postJobService', function ($query) use ($postRequestId) {
                $query->where('post_request_id', $postRequestId);
            });
        }
        if ($request->has('servicepackage')) {
            $servicepackage = $request->servicepackage;
            $query = Service::whereHas('servicePackage', function ($query) use ($servicepackage) {
                $query->where('service_package_id', $servicepackage);
            });
        }
       
        
    
        return $datatable->eloquent($query)
            ->addColumn('check', function ($row) {
                return '<input type="checkbox" class="form-check-input select-table-row" id="datatable-row-' . $row->id . '" name="datatable_ids[]" value="' . $row->id . '" data-type="service" onclick="dataTableRowCheck(' . $row->id . ',this)">';
            })
            ->editColumn('name', function ($query) {
                if (auth()->user()->can('service edit')) {
                    $link = '<a class="btn-link btn-link-hover" href=' . route('service.create', ['id' => $query->id]) . '>' . $query->name . '</a>';
                } else {
                    $link = $query->name;
                }
                return $link;
            })
            ->editColumn('category_id', function ($query) {
                return ($query->category_id != null && isset($query->category)) ? $query->category->name : '-';
            })
            ->filterColumn('category_id', function ($query, $keyword) {
                $query->whereHas('category', function ($q) use ($keyword) {
                    $q->where('name', 'like', '%' . $keyword . '%');
                });
            })
            ->orderColumn('category_id', function ($query, $order) {
                $query->join('categories', 'categories.id', '=', 'services.category_id')
                    ->orderBy('categories.name', $order);
            })
            ->editColumn('provider_id', function ($query) {
                return view('service.service', compact('query'));
            })
            ->filterColumn('provider_id', function ($query, $keyword) {
                $query->whereHas('providers', function ($q) use ($keyword) {
                    $q->where('display_name', 'like', '%' . $keyword . '%');
                });
            })
            ->orderColumn('provider_id', function ($query, $order) {
                $query->select('services.*')
                    ->join('users as providers', 'providers.id', '=', 'services.provider_id')
                    ->orderBy('providers.display_name', $order);
            })
            ->editColumn('price', function ($query) {
                return getPriceFormat($query->price) . '-' . ucfirst($query->type);
            })
            ->editColumn('discount', function ($query) {
                return $query->discount ? $query->discount . '%' : '-';
            })
            ->editColumn('service_request_status', function ($query) {
                $status = $query->service_request_status; 
                $badgeText = __('messages.unknown'); 
                $badgeClass = 'badge-secondary';
            
                if ($status === "pending") {
                    $badgeText = __('messages.pending'); 
                    $badgeClass = 'badge badge-warning text-warning bg-warning-subtle';
                } elseif ($status === "reject") {
                    $badgeText = __('messages.reject'); 
                    $badgeClass = 'badge-danger';
                } elseif ($status === "approve") {
                    $badgeText = __('messages.approve'); 
                    $badgeClass = 'badge badge-active text-success bg-success-subtle';
                }
            
                return '<span class="badge ' . $badgeClass . '" id="datatable-row-' . $query->id . '">' . $badgeText . '</span>';
            })
            
            
            ->addColumn('action', function ($data) {
                $actionButtons = '';

                if ($data->trashed()) {
                    // Service is soft-deleted: Show restore and delete options
                    if (auth()->user()->hasAnyRole(['admin', 'provider'])) {
                        $restoreUrl = route('service.action', ['id' => $data->id, 'type' => 'restore']);
                        $forceDeleteUrl = route('service.action', ['id' => $data->id, 'type' => 'forcedelete']);
    
                        $actionButtons .= '
                            <a href="' . $restoreUrl . '" class="me-2 restore-btn"
                                title="' . __('messages.restore_form_title', ['form' => __('messages.service')]) . '"
                                data--submit="confirm_form"
                                data--confirmation="true"
                                data--ajax="true"
                                data-title="' . __('messages.restore_form_title', ['form' => __('messages.service')]) . '"
                                data-message="' . __('messages.restore_msg') . '"
                                data-datatable="reload">
                                <i class="fas fa-redo text-primary"></i>
                            </a>';
    
                        $actionButtons .= '
                            <a href="' . $forceDeleteUrl . '" class="me-2"
                                title="' . __('messages.forcedelete_form_title', ['form' => __('messages.service')]) . '"
                                data--submit="confirm_form"
                                data--confirmation="true"
                                data--ajax="true"
                                data-title="' . __('messages.forcedelete_form_title', ['form' => __('messages.service')]) . '"
                                data-message="' . __('messages.forcedelete_msg') . '"
                                data-datatable="reload">
                                <i class="far fa-trash-alt text-danger"></i>
                            </a>';
                    }
                } else {
                    // Service is not deleted
                    if ($data->service_request_status === "approve") {
                        // Show view (eye) icon for approved services
                        $actionButtons .= '<a href="' . route('service.create', ['id' => $data->id]) . '" class="btn btn-link p-0" title="' . __('messages.view') . '" data-bs-toggle="tooltip">
                            <i class="far fa-eye text-primary" ></i>
                        </a>';
                    } elseif ($data->service_request_status === "reject") {
                        // Show delete option for rejected services
                        $actionButtons .= '<a href="javascript:void(0);" class="trash-btn" data-id="' . $data->id . '" title="' . __('messages.delete') . '" data-bs-toggle="tooltip">
                            <i class="far fa-trash-alt text-danger"></i>
                        </a>';
                    } elseif ($data->service_request_status === "pending") {
                        // Show approve/reject buttons for pending services
                        if (auth()->user()->user_type === 'admin' || auth()->user()->user_type === 'demo_admin') {
                            $approveButton = '<button class="btn btn-link approve-btn py-0 px-1" data-id="' . $data->id . '" title="' . __('messages.approve') . '" data-bs-toggle="tooltip">
                                <i class="fas fa-check text-success"></i>
                            </button>';
                            $rejectButton = '<button class="btn btn-link reject-btn py-0 px-1" data-id="' . $data->id . '" title="' . __('messages.reject') . '" data-bs-toggle="tooltip">
                                <i class="fas fa-times text-danger"></i>
                            </button>';
                            $actionButtons .= '<div class="d-flex align-items-center gap-2">' . $approveButton . ' ' . $rejectButton . '</div>';
                        }
                    }
                }
    
                return '<div class="d-flex align-items-center">' . $actionButtons . '</div>';
            })           
            ->rawColumns(['action','service_request_status', 'status', 'check', 'name'])
            ->toJson();
    }
    
    public function updateStatus(Request $request)
    {
        
        $serviceId = $request->id;
        $status = $request->status;
        $service = Service::find($serviceId);

        if ($service) {
            $service->service_request_status = ($status == 'approved') ? "approve" : "reject";
            $service->reject_reason= $request->reason;

            $service->save();
    
            $provider = User::find($service->provider_id);

            $activity_data = [
                'activity_type' => ($status == 'approved') ? 'service_request_approved' : 'service_request_reject',
                'service_id' => $service->id,
                'id' => $service->id,
                'provider_id' => $service->provider_id,
                'provider_name' => $provider->display_name?? 'Unknown User',
                'user_name' => $provider?->display_name ?? 'Unknown User',
                'service_name' => $service->name,
                'reason' => $request->reason, 
            ];
            $this->sendNotification($activity_data);
    
            return response()->json([
                'success' => true,
                'status' => $status,
                'serviceId' => $serviceId,
                'providerId' => $service->provider_id,
            ]);
        }
    
        return response()->json(['success' => false, 'message' => 'Service not found']);
    }
    

    public function providerServiceRequest(Request $request)
    {
        $auth_user = auth()->user(); 
        $filter = [
            'status' => 'Pending',  
            'provider_id' => $auth_user->id, 
        ];
        
        $pageTitle = __('messages.service_request', ['form' => __('messages.service')]);
        $assets = ['datatable'];
        
        $services = Service::where('provider_id', $auth_user->id)
                            ->get();
        
        return view('service.provider-service-request', compact('pageTitle', 'auth_user', 'assets', 'services', 'filter'));
    }

    public function request_bulk_action(Request $request)
    {
        $ids = explode(',', $request->rowIds);

        $actionType = $request->action_type;

        $message = 'Bulk Action Updated';


        switch ($actionType) {
            case 'change-status':
                if (in_array($request->status, ['pending', 'reject','approve'])) {
                    Service::whereIn('id', $ids)->update(['service_request_status' => $request->status]);
                    $message = __('messages.bulk_service_status_updated');
                } else {
                    return response()->json(['status' => false, 'message' => __('messages.invalid_status')]);
                }
                break;            

            case 'delete':
                Service::whereIn('id', $ids)->delete();
                $message = __('messages.bulk_service_deleted');
                break;

            case 'restore':
                Service::whereIn('id', $ids)->restore();
                $message = __('messages.bulk_service_restored');
                break;

            case 'permanently-delete':
                Service::whereIn('id', $ids)->forceDelete();
                $message = __('messages.bulk_service_permanently_deleted');
                break;

            default:
            return response()->json(['status' => false, 'message' => __('messages.action_invalid')]);
            break;
        }

        return response()->json(['status' => true, 'message' => $message]);
    }


    public function bulk_action(Request $request)
    {
        $ids = explode(',', $request->rowIds);

        $actionType = $request->action_type;

        $message = 'Bulk Action Updated';


        switch ($actionType) {
            case 'change-status':
                $branches = Service::whereIn('id', $ids)->update(['status' => $request->status]);
                $message = 'Bulk Service Status Updated';
                break;

            case 'delete':
                Service::whereIn('id', $ids)->delete();
                $message = 'Bulk Service Deleted';
                break;

            case 'restore':
                Service::whereIn('id', $ids)->restore();
                $message = 'Bulk Service Restored';
                break;

            case 'permanently-delete':
                Service::whereIn('id', $ids)->forceDelete();
                $message = 'Bulk Service Permanently Deleted';
                break;

            default:
                return response()->json(['status' => false, 'message' => 'Action Invalid']);
                break;
        }

        return response()->json(['status' => true, 'message' => $message]);
    }



    /* user service list */
    public function getUserServiceList(Request $request)
    {
        $filter = [
            'status' => $request->status,
        ];
        $pageTitle = __('messages.list_form_title',['form' => __('messages.service')] );
        $auth_user = authSession();
        $assets = ['datatable'];
        return view('service.user_service_list', compact('pageTitle','auth_user','assets','filter'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if (!auth()->user()->can('service add')) {
            return redirect()->back()->withErrors(trans('messages.demo_permission_denied'));
        }
        $id = $request->id;

        $auth_user = authSession();
        $language_array = $this->languagesArray();
        $servicedata = Service::find($id);

        $visittype = config('constant.VISIT_TYPE');

        $settingdata = Setting::where('type','=','service-configurations')->first();

        $advancedPaymentSetting=0;
        $slotservice = 0;
        $digital_services = 0;

          if($settingdata) {

              $settings = json_decode($settingdata->value, true);

              $advancedPaymentSetting = $settings['advance_payment'];
              $slotservice = $settings['slot_service'];
              $digital_services = $settings['digital_services'];

          }

            if ($digital_services == 1) {
                $visittype = [
                    'on_site' => 'On Site',
                    'ONLINE' => 'Online',
                ];
            } else {
                $visittype = [
                    'ON_SITE' => 'On Site',
                ];
            }


        $pageTitle = __('messages.update_form_title',['form'=> __('messages.service')]);

        if($servicedata == null){
            $pageTitle = __('messages.add_button_form',['form' => __('messages.service')]);
            $servicedata = new Service;
            $services['is_service_request'] = 1;
        }else{
            if ($servicedata->provider_id !== auth()->user()->id && !auth()->user()->hasRole(['admin', 'demo_admin'])) {
                return redirect(route('service.index'))->withErrors(trans('messages.demo_permission_denied'));
            }
        }

        return view('service.create', compact('language_array','pageTitle' ,'servicedata' ,'auth_user' , 'advancedPaymentSetting','visittype','slotservice'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ServiceRequest $request)
    {
        if(demoUserPermission()){
            return  redirect()->back()->withErrors(trans('messages.demo_permission_denied'));
        }

        $services = $request->all();
        
        $language_option = sitesetupSession('get')->language_option ?? ["ar","nl","en","fr","de","hi","it"];
        $primary_locale = app()->getLocale() ?? 'en';
        $translatableAttributes = ['name', 'description'];

        $services['service_type'] = !empty($request->service_type) ? $request->service_type : 'service';
        $services['provider_id'] = !empty($request->provider_id) ? $request->provider_id : auth()->user()->id;
        if(auth()->user()->hasRole('user')){
            $services['service_type'] = 'user_post_service';
        }

        if($request->id == null && default_earning_type() === 'subscription'){
           $exceed =  get_provider_plan_limit($services['provider_id'],'service');
           if(!empty($exceed)){
            if($exceed == 1){
                $message = __('messages.limit_exceed',['name'=>__('messages.service')]);
            }else{
                 $message = __('messages.not_in_plan',['name'=>__('messages.service')]);
            }
             if($request->is('api/*')){
                 return comman_message_response($message);
             }else{
                 return  redirect()->back()->withErrors($message);
             }
           }
        }

        if($request->id == null){
            $services['added_by'] =  !empty($request->added_by) ? $request->added_by :auth()->user()->id;
            $services['is_service_request'] = 1;
        }

        $services['provider_id'] = !empty( $services['provider_id'] ) ?  $services['provider_id']     : auth()->user()->id;
        if(!empty($services['is_featured']) && $services['is_featured'] == 1){
            $exceed =  get_provider_plan_limit($services['provider_id'],'featured_service');
            if(!empty($exceed)){
                if($exceed == 1){
                    $message = __('messages.limit_exceed',['name'=>__('messages.featured_service')]);
                }else{
                        $message = __('messages.not_in_plan',['name'=>__('messages.featured_service')]);
                }
                if($request->is('api/*')){
                    return comman_message_response($message);
                }else{
                    return  redirect()->back()->withErrors($message);
                }
            }
        }

        if(!$request->is('api/*')) {
            $services['is_featured'] = 0;
            $services['is_slot'] = 0;
            $services['is_enable_advance_payment'] = 0;

            if($request->has('is_featured')){
                $services['is_featured'] = 1;
            }
            if($request->has('is_enable_advance_payment')){
                $services['is_enable_advance_payment'] = 1;
            }
            if($request->has('is_slot')){
                $services['is_slot'] = 1;
            }

        }
        if(!empty($request->advance_payment_amount)){
            $services['advance_payment_amount'] = $request->advance_payment_amount;
        }
        $result = Service::updateOrCreate(['id' => $request->id], $services);
        if ($request->is('api/*')) {
            // Decode API JSON string
            $services['translations'] = json_decode($services['translations'] ?? '{}', true);
        } elseif (isset($services['translations']) && is_array($services['translations'])) {
            // Web request already provides translations as an array
            $services['translations'] = $services['translations'];
        }
        $result->saveTranslations($services, $translatableAttributes, $language_option, $primary_locale);
        if($result->providerServiceAddress()->count() > 0)
        {
            $result->providerServiceAddress()->delete();
        }

        if($request->provider_address_id != null) {
            foreach($request->provider_address_id as $address) {
                $provider_service_address = [
                    'service_id'   => $result->id,
                    'provider_address_id'   => $address,
                ];
                $result->providerServiceAddress()->insert($provider_service_address);
            }
        }

        if($request->is('api/*')){
			if($request->has('attachment_count')) {
				for($i = 0 ; $i < $request->attachment_count ; $i++){
					$attachment = "service_attachment_".$i;
					if($request->$attachment != null){
						$file[] = $request->$attachment;
					}
				}
				storeMediaFile($result,$file, 'service_attachment');
			}

		}else{
            if ($request->hasFile('service_attachment')) {
                storeMediaFile($result, $request->file('service_attachment'), 'service_attachment');
            } elseif (!getMediaFileExit($result, 'service_attachment')) {
                return redirect()->route('service.create', ['id' => $result->id])
                ->withErrors(['service_attachment' => 'The attachments field is required.'])
                ->withInput();
            }	
		}
        $message = __('messages.update_form',[ 'form' => __('messages.service') ] );
		if($result->wasRecentlyCreated){
			$message = __('messages.save_form',[ 'form' => __('messages.service') ] );
		}

        $activity_data = [
            'type' => 'service_request',
            'activity_type' => 'service_request',
            'activity_message' => __('messages.new_service_request', ['name' => $result->name ?? __('messages.service')]),
            'id' => $result->id,
            'service_id' => $result->id,
            'service_name' => $result->name,
            'provider_id' => $result->provider_id,
            'datetime' => now()->format('Y-m-d H:i:s'),
        ];
        $this->sendNotification($activity_data);
        

        if($request->is('api/*')) {
            $response = [
                'message'=>$message,
                'service_id' => $result->id
            ];
            return comman_custom_response($response);
		}
		return redirect(route('service.provider-service-request'))->withSuccess($message);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $auth_user = authSession();
        if ($id != auth()->user()->id && !auth()->user()->hasRole(['admin', 'demo_admin'])) {
            return redirect(route('home'))->withErrors(trans('messages.demo_permission_denied'));
        }
        $tabpage = 'all-plan';
        $providerdata = User::with('providerDocument')->where('user_type', 'provider')->where('id', $id)->first();
        if (empty($providerdata)) {
            $msg = __('messages.not_found_entry', ['name' => __('messages.provider')]);
            return redirect(route('provider.index'))->withError($msg);
        }
        $pageTitle = __('messages.view_form_title', ['form' => __('messages.provider')]);

        return view('service.view', compact('pageTitle','providerdata', 'auth_user', 'tabpage'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(demoUserPermission()){
            if(request()->is('api/*')){
                return comman_message_response( __('messages.demo_permission_denied') );
            }
            return  redirect()->back()->withErrors(trans('messages.demo_permission_denied'));
        }
        $service = Service::find($id);
        $msg= __('messages.msg_fail_to_delete',['item' => __('messages.service')] );

        if($service!='') {
            $service->delete();
            $msg= __('messages.msg_deleted',['name' => __('messages.service')] );
        }
        if(request()->is('api/*')){
            return comman_custom_response(['message'=> $msg , 'status' => true]);
        }
        return comman_custom_response(['message'=> $msg, 'status' => true]);
    }
    public function action(Request $request){
        $id = $request->id;
        $service = Service::withTrashed()->where('id',$id)->first();
        $msg = __('messages.not_found_entry',['name' => __('messages.service')] );
        if($request->type === 'restore'){
            $service->restore();
            $msg = __('messages.msg_restored',['name' => __('messages.service')] );
        }

        if($request->type === 'forcedelete'){
            $service->forceDelete();
            $msg = __('messages.msg_forcedelete',['name' => __('messages.service')] );
        }

        return comman_custom_response(['message'=> $msg , 'status' => true]);
    }

    public function saveFavouriteService(Request $request)
    {
        $user_favourite = $request->all();

        $result = UserFavouriteService::updateOrCreate(['id' => $request->id], $user_favourite);

        $message = __('messages.update_form',[ 'form' => __('messages.favourite') ] );
		if($result->wasRecentlyCreated){
			$message = __('messages.save_form',[ 'form' => __('messages.favourite') ] );
		}

        return  redirect()->back()->withSuccess($message);
    }

    public function deleteFavouriteService(Request $request)
    {

        $service_rating = UserFavouriteService::where('user_id',$request->user_id)->where('service_id',$request->service_id)->delete();

        $message = __('messages.delete_form',[ 'form' => __('messages.favourite') ] );

        return  redirect()->back()->withSuccess($message);
    }
}
