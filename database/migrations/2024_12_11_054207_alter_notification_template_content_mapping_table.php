<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\NotificationTemplate;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('notification_template_content_mapping', function (Blueprint $table) {
            $columns = [
                'mail_template_detail' => ['type' => 'longText', 'nullable' => true],
                'mail_subject' => ['type' => 'string', 'nullable' => true],
                'sms_template_detail' => ['type' => 'longText', 'nullable' => true],
                'sms_subject' => ['type' => 'string', 'nullable' => true],
                'whatsapp_template_detail' => ['type' => 'longText', 'nullable' => true],
                'whatsapp_subject' => ['type' => 'string', 'nullable' => true],
            ];
        
            foreach ($columns as $column => $attributes) {
                if (!Schema::hasColumn('notification_template_content_mapping', $column)) {
                    $type = $attributes['type'];
                    $columnDefinition = $table->$type($column);
                    if (!empty($attributes['nullable'])) {
                        $columnDefinition->nullable();
                    }
                }
            }
        });

        // Enable foreign key checks!
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Delete existing data
        \DB::table('notification_templates')->delete();
        \DB::table('notification_template_content_mapping')->delete();

        $template = NotificationTemplate::create([
            'type' => 'add_booking',
            'name' => 'add_booking',
            'label' => 'Booking confirmation',
            'status' => 1,
            'to' => '["admin","provider"]',
            'channels' => ['PUSH_NOTIFICATION' => '1','IS_MAIL' => '0', 'IS_SMS' => '1', 'IS_WHATSAPP' => '1'],
        ]);
        $template->defaultNotificationTemplateMap()->create([
            'language' => 'en',
            'notification_link' => '',
            'notification_message' => '',
            'user_type' => 'admin',
            'status' => 1,
            'subject' => 'New Booking Received',
            'mail_subject' => 'New Booking Received',
            'whatsapp_subject' => 'New Booking Received',
            'sms_subject' => 'New Booking Received',
            'template_detail' => '<p>New booking #[[ booking_id ]] - [[ customer_name ]] has booked [[ booking_services_name ]].</p>',
            'whatsapp_template_detail' => '<p>New booking #[[ booking_id ]] - [[ customer_name ]] has booked [[ booking_services_name ]].</p>',
            'sms_template_detail' => '<p>New booking #[[ booking_id ]] - [[ customer_name ]] has booked [[ booking_services_name ]].</p>',
            'mail_template_detail' => '<p>New booking #[[ booking_id ]] - [[ customer_name ]] has booked [[ booking_services_name ]].</p>',
        ]);
        $template->defaultNotificationTemplateMap()->create([
            'language' => 'en',
            'notification_link' => '',
            'notification_message' => '',
            'user_type' => 'provider',
            'status' => 1,
            'subject' => 'New Booking Received',
            'mail_subject' => 'New Booking Received',
            'whatsapp_subject' => 'New Booking Received',
            'sms_subject' => 'New Booking Received',
            'template_detail' => '<p>New booking #[[ booking_id ]] - [[ customer_name ]] has booked [[ booking_services_name ]].</p>',
            'whatsapp_template_detail' => '<p>New booking #[[ booking_id ]] - [[ customer_name ]] has booked [[ booking_services_name ]].</p>',
            'sms_template_detail' => '<p>New booking #[[ booking_id ]] - [[ customer_name ]] has booked [[ booking_services_name ]].</p>',
            'mail_template_detail' => '<p>New booking #[[ booking_id ]] - [[ customer_name ]] has booked [[ booking_services_name ]].</p>',
        ]);

        $template->defaultNotificationTemplateMap()->create([
            'language' => 'en',
            'notification_link' => '',
            'notification_message' => '',
            'user_type' => 'handyman',
            'status' => 1,
            'subject' => 'Booking Assigned!',
            'mail_subject' => 'Booking Assigned!',
            'whatsapp_subject' => 'Booking Assigned!',
            'sms_subject' => 'Booking Assigned!',
            'template_detail' => '<p>#[[ booking_id ]] - You have been assigned to provide [[ booking_services_name ]]. Please proceed accordingly.</p>',
            'whatsapp_template_detail' => '<p>#[[ booking_id ]] - You have been assigned to provide [[ booking_services_name ]]. Please proceed accordingly.</p>',
            'sms_template_detail' => '<p>#[[ booking_id ]] - You have been assigned to provide [[ booking_services_name ]]. Please proceed accordingly.</p>',
            'mail_template_detail' => '<p>#[[ booking_id ]] - You have been assigned to provide [[ booking_services_name ]]. Please proceed accordingly.</p>',

        ]);

        $template = NotificationTemplate::create([
            'type' => 'assigned_booking',
            'name' => 'assigned_booking',
            'label' => 'Booking Assigned',
            'status' => 1,
            'to' => '["handyman","user","provider"]',
            'channels' => ['PUSH_NOTIFICATION' => '1','IS_MAIL' => '0', 'IS_SMS' => '1', 'IS_WHATSAPP' => '1'],
        ]);
        $template->defaultNotificationTemplateMap()->create([
            'language' => 'en',
            'notification_link' => '',
            'notification_message' => '',
            'user_type' => 'handyman',
            'status' => 1,
            'subject' => 'Booking Assigned!',
            'mail_subject' => 'Booking Assigned!',
            'whatsapp_subject' => 'Booking Assigned!',
            'sms_subject' => 'Booking Assigned!',
            'template_detail' => '<p>#[[ booking_id ]] - You have been assigned to provide [[ booking_services_name ]]. Please proceed accordingly.</p>',
            'whatsapp_template_detail' => '<p>#[[ booking_id ]] - You have been assigned to provide [[ booking_services_name ]]. Please proceed accordingly.</p>',
            'sms_template_detail' => '<p>#[[ booking_id ]] - You have been assigned to provide [[ booking_services_name ]]. Please proceed accordingly.</p>',
            'mail_template_detail' => '<p>#[[ booking_id ]] - You have been assigned to provide [[ booking_services_name ]]. Please proceed accordingly.</p>',

        ]);
        $template->defaultNotificationTemplateMap()->create([
            'language' => 'en',
            'notification_link' => '',
            'notification_message' => '',
            'user_type' => 'user',
            'status' => 1,
            'subject' => 'Booking Assigned!',
            'whatsapp_subject' => 'Booking Assigned!',
            'sms_subject' => 'Booking Assigned!',
            'mail_subject' => 'Booking Assigned!',
            'template_detail' => '<p>#[[ booking_id ]] - [[ assignee_name ]] has been assigned to [[ booking_services_name ]].</p>',
            'whatsapp_template_detail' => '<p>#[[ booking_id ]] - [[ assignee_name ]] has been assigned to [[ booking_services_name ]].</p>',
            'sms_template_detail' => '<p>#[[ booking_id ]] - [[ assignee_name ]] has been assigned to [[ booking_services_name ]].</p>',
            'mail_template_detail' => '<p>#[[ booking_id ]] - [[ assignee_name ]] has been assigned to [[ booking_services_name ]].</p>',
            
        ]);
        $template->defaultNotificationTemplateMap()->create([
            'language' => 'en',
            'notification_link' => '',
            'notification_message' => '',
            'user_type' => 'provider',
            'status' => 1,
            'subject' => 'Booking Assigned!',
            'mail_subject' => 'Booking Assigned!',
            'whatsapp_subject' => 'Booking Assigned!',
            'sms_subject' => 'Booking Assigned!',
            'template_detail' => '<p>#[[ booking_id ]] - [[ assignee_name ]] has been assigned to [[ booking_services_name ]].</p>',
            'whatsapp_template_detail' => '<p>#[[ booking_id ]] - [[ assignee_name ]] has been assigned to [[ booking_services_name ]].</p>',
            'sms_template_detail' => '<p>#[[ booking_id ]] - [[ assignee_name ]] has been assigned to [[ booking_services_name ]].</p>',
            'mail_template_detail' => '<p>#[[ booking_id ]] - [[ assignee_name ]] has been assigned to [[ booking_services_name ]].</p>',
        ]);

        $template = NotificationTemplate::create([
            'type' => 'update_booking_status',
            'name' => 'update_booking_status',
            'label' => 'Booking Update',
            'status' => 1,
            'to' => '["admin", "provider" , "handyman" , "user"]',
            'channels' => ['PUSH_NOTIFICATION' => '1','IS_MAIL' => '0', 'IS_SMS' => '1', 'IS_WHATSAPP' => '1'],
        ]);
        $template->defaultNotificationTemplateMap()->create([
            'language' => 'en',
            'notification_link' => '',
            'notification_message' => '',
            'user_type' => 'admin',
            'status' => 1,
            'status' => 1,
            'subject' => 'Booking Update',
            'mail_subject' => 'Booking Update',
            'whatsapp_subject' => 'Booking Update',
            'sms_subject' => 'Booking Update',
            'template_detail' => '<p>#[[ booking_id ]] - Status for [[ booking_services_name ]] has changed to [[ booking_status ]].</p>',
            'whatsapp_template_detail' => '<p>#[[ booking_id ]] - Status for [[ booking_services_name ]] has changed to [[ booking_status ]].</p>',
            'sms_template_detail' => '<p>#[[ booking_id ]] - Status for [[ booking_services_name ]] has changed to [[ booking_status ]].</p>',
            'mail_template_detail' => '<p>#[[ booking_id ]] - Status for [[ booking_services_name ]] has changed to [[ booking_status ]].</p>',

        ]);
        $template->defaultNotificationTemplateMap()->create([
            'language' => 'en',
            'notification_link' => '',
            'notification_message' => '',
            'user_type' => 'provider',
            'status' => 1,
            'subject' => 'Booking Update',
            'mail_subject' => 'Booking Update',
            'whatsapp_subject' => 'Booking Update',
            'sms_subject' => 'Booking Update',
            'template_detail' => '<p>#[[ booking_id ]] - Status for [[ booking_services_name ]] has changed to [[ booking_status ]].</p>',
            'whatsapp_template_detail' => '<p>#[[ booking_id ]] - Status for [[ booking_services_name ]] has changed to [[ booking_status ]].</p>',
            'sms_template_detail' => '<p>#[[ booking_id ]] - Status for [[ booking_services_name ]] has changed to [[ booking_status ]].</p>',
            'mail_template_detail' => '<p>#[[ booking_id ]] - Status for [[ booking_services_name ]] has changed to [[ booking_status ]].</p>',
        ]);
        $template->defaultNotificationTemplateMap()->create([
            'language' => 'en',
            'notification_link' => '',
            'notification_message' => '',
            'user_type' => 'handyman',
            'status' => 1,
            'subject' => 'Booking Update',
            'mail_subject' => 'Booking Update',
            'whatsapp_subject' => 'Booking Update',
            'sms_subject' => 'Booking Update',
            'template_detail' => '<p>#[[ booking_id ]] - Status for [[ booking_services_name ]] has changed to [[ booking_status ]].</p>',
            'whatsapp_template_detail' => '<p>#[[ booking_id ]] - Status for [[ booking_services_name ]] has changed to [[ booking_status ]].</p>',
            'sms_template_detail' => '<p>#[[ booking_id ]] - Status for [[ booking_services_name ]] has changed to [[ booking_status ]].</p>',
            'mail_template_detail' => '<p>#[[ booking_id ]] - Status for [[ booking_services_name ]] has changed to [[ booking_status ]].</p>',
        ]);
        $template->defaultNotificationTemplateMap()->create([
            'language' => 'en',
            'notification_link' => '',
            'notification_message' => '',
            'user_type' => 'user',
            'status' => 1,
            'subject' => 'Booking Confirmation',
            'mail_subject' => 'Booking Confirmation',
            'whatsapp_subject' => 'Booking Confirmation',
            'sms_subject' => 'Booking Confirmation',
            'template_detail' => '<p>#[[ booking_id ]] - Status for [[ booking_services_name ]] has changed to [[ booking_status ]].</p>',
            'whatsapp_template_detail' => '<p>#[[ booking_id ]] - Status for [[ booking_services_name ]] has changed to [[ booking_status ]].</p>',
            'sms_template_detail' => '<p>#[[ booking_id ]] - Status for [[ booking_services_name ]] has changed to [[ booking_status ]].</p>',
            'mail_template_detail' => '<p>#[[ booking_id ]] - Status for [[ booking_services_name ]] has changed to [[ booking_status ]].</p>',

        ]);

        $template = NotificationTemplate::create([
            'type' => 'cancel_booking',
            'name' => 'cancel_booking',
            'label' => 'Cancel On Booking',
            'status' => 1,
            'to' => '["admin", "provider" , "handyman" , "user"]',
            'channels' => ['PUSH_NOTIFICATION' => '1','IS_MAIL' => '0', 'IS_SMS' => '1', 'IS_WHATSAPP' => '1'],
        ]);
        $template->defaultNotificationTemplateMap()->create([
            'language' => 'en',
            'notification_link' => '',
            'notification_message' => '',
            'user_type' => 'admin',
            'status' => 1,
            'subject' => 'Booking Cancelled',
            'mail_subject' => 'Booking Cancelled',
            'whatsapp_subject' => 'Booking Cancelled',
            'sms_subject' => 'Booking Cancelled',
            'template_detail' => '<p>#[[ booking_id ]] - [[ booking_services_name ]] has been cancelled.</p>',
            'whatsapp_template_detail' => '<p>#[[ booking_id ]] - [[ booking_services_name ]] has been cancelled.</p>',
            'sms_template_detail' => '<p>#[[ booking_id ]] - [[ booking_services_name ]] has been cancelled.</p>',
            'mail_template_detail' => '<p>#[[ booking_id ]] - [[ booking_services_name ]] has been cancelled.</p>',
        ]);
        $template->defaultNotificationTemplateMap()->create([
            'language' => 'en',
            'notification_link' => '',
            'notification_message' => '',
            'user_type' => 'provider',
            'status' => 1,
            'subject' => 'Booking Cancelled',
            'mail_subject' => 'Booking Cancelled',
            'whatsapp_subject' => 'Booking Cancelled',
            'sms_subject' => 'Booking Cancelled',
            'template_detail' => '<p>#[[ booking_id ]] - [[ booking_services_name ]] has been cancelled.</p>',
            'whatsapp_template_detail' => '<p>#[[ booking_id ]] - [[ booking_services_name ]] has been cancelled.</p>',
            'sms_template_detail' => '<p>#[[ booking_id ]] - [[ booking_services_name ]] has been cancelled.</p>',
            'mail_template_detail' => '<p>#[[ booking_id ]] - [[ booking_services_name ]] has been cancelled.</p>',

        ]);
        $template->defaultNotificationTemplateMap()->create([
            'language' => 'en',
            'notification_link' => '',
            'notification_message' => '',
            'user_type' => 'handyman',
            'status' => 1,
            'subject' => 'Booking Cancelled',
            'mail_subject' => 'Booking Cancelled',
            'whatsapp_subject' => 'Booking Cancelled',
            'sms_subject' => 'Booking Cancelled',
            'template_detail' => '<p>#[[ booking_id ]] - [[ booking_services_name ]] has been cancelled.</p>',
            'whatsapp_template_detail' => '<p>#[[ booking_id ]] - [[ booking_services_name ]] has been cancelled.</p>',
            'sms_template_detail' => '<p>#[[ booking_id ]] - [[ booking_services_name ]] has been cancelled.</p>',
            'mail_template_detail' => '<p>#[[ booking_id ]] - [[ booking_services_name ]] has been cancelled.</p>',
        ]);
        $template->defaultNotificationTemplateMap()->create([
            'language' => 'en',
            'notification_link' => '',
            'notification_message' => '',
            'user_type' => 'user',
            'status' => 1,
            'subject' => 'Booking Cancelled',
            'mail_subject' => 'Booking Cancelled',
            'whatsapp_subject' => 'Booking Cancelled',
            'sms_subject' => 'Booking Cancelled',
            'template_detail' => '<p>#[[ booking_id ]] - [[ booking_services_name ]] has been cancelled.</p>',
            'whatsapp_template_detail' => '<p>#[[ booking_id ]] - [[ booking_services_name ]] has been cancelled.</p>',
            'sms_template_detail' => '<p>#[[ booking_id ]] - [[ booking_services_name ]] has been cancelled.</p>',
            'mail_template_detail' => '<p>#[[ booking_id ]] - [[ booking_services_name ]] has been cancelled.</p>',
        ]);

        $template = NotificationTemplate::create([
            'type' => 'payment_message_status',
            'name' => 'payment_message_status',
            'label' => 'Payment Status Update',
            'status' => 1,
            'to' => '["user","handyman","provider","admin"]',
            'channels' => ['PUSH_NOTIFICATION' => '1','IS_MAIL' => '0', 'IS_SMS' => '1', 'IS_WHATSAPP' => '1'],
        ]);
        $template->defaultNotificationTemplateMap()->create([
            'language' => 'en',
            'notification_link' => '',
            'notification_message' => '',
            'user_type' => 'user',
            'status' => 1,
            'subject' => 'Payment Status Update',
            'mail_subject' => 'Payment Status Update',
            'whatsapp_subject' => 'Payment Status Update',
            'sms_subject' => 'Payment Status Update',
            'template_detail' => '<p>#[[ booking_id ]] - Payment For [[ booking_services_name ]] changed to [[ payment_status ]].</p>',
            'whatsapp_template_detail' => '<p>#[[ booking_id ]] - Payment For [[ booking_services_name ]] changed to [[ payment_status ]].</p>',
            'sms_template_detail' => '<p>#[[ booking_id ]] - Payment For [[ booking_services_name ]] changed to [[ payment_status ]].</p>',
            'mail_template_detail' => '<p>#[[ booking_id ]] - Payment For [[ booking_services_name ]] changed to [[ payment_status ]].</p>',

        ]);

        $template->defaultNotificationTemplateMap()->create([
            'language' => 'en',
            'notification_link' => '',
            'notification_message' => '',
            'user_type' => 'admin',
            'status' => 1,
            'subject' => 'Payment Status Update',
            'mail_subject' => 'Payment Status Update',
            'whatsapp_subject' => 'Payment Status Update',
            'sms_subject' => 'Payment Status Update',
            'template_detail' => '<p>#[[ booking_id ]] - Payment For [[ booking_services_name ]] changed to [[ payment_status ]].</p>',
            'whatsapp_template_detail' => '<p>#[[ booking_id ]] - Payment For [[ booking_services_name ]] changed to [[ payment_status ]].</p>',
            'sms_template_detail' => '<p>#[[ booking_id ]] - Payment For [[ booking_services_name ]] changed to [[ payment_status ]].</p>',
            'mail_template_detail' => '<p>#[[ booking_id ]] - Payment For [[ booking_services_name ]] changed to [[ payment_status ]].</p>',
            
        ]);
        $template->defaultNotificationTemplateMap()->create([
            'language' => 'en',
            'notification_link' => '',
            'notification_message' => '',
            'user_type' => 'provider',
            'status' => 1,
            'subject' => 'Payment Status Update',
            'mail_subject' => 'Payment Status Update',
            'whatsapp_subject' => 'Payment Status Update',
            'sms_subject' => 'Payment Status Update',
            'template_detail' => '<p>#[[ booking_id ]] - Payment For [[ booking_services_name ]] changed to [[ payment_status ]].</p>',
            'whatsapp_template_detail' => '<p>#[[ booking_id ]] - Payment For [[ booking_services_name ]] changed to [[ payment_status ]].</p>',
            'sms_template_detail' => '<p>#[[ booking_id ]] - Payment For [[ booking_services_name ]] changed to [[ payment_status ]].</p>',
            'mail_template_detail' => '<p>#[[ booking_id ]] - Payment For [[ booking_services_name ]] changed to [[ payment_status ]].</p>',
            
        ]);

        $template->defaultNotificationTemplateMap()->create([
            'language' => 'en',
            'notification_link' => '',
            'notification_message' => '',
            'user_type' => 'handyman',
            'status' => 1,
            'subject' => 'Payment Status Update',
            'mail_subject' => 'Payment Status Update',
            'whatsapp_subject' => 'Payment Status Update',
            'sms_subject' => 'Payment Status Update',
            'template_detail' => '<p>#[[ booking_id ]] - Payment For [[ booking_services_name ]] changed to [[ payment_status ]].</p>',
            'whatsapp_template_detail' => '<p>#[[ booking_id ]] - Payment For [[ booking_services_name ]] changed to [[ payment_status ]].</p>',
            'sms_template_detail' => '<p>#[[ booking_id ]] - Payment For [[ booking_services_name ]] changed to [[ payment_status ]].</p>',
            'mail_template_detail' => '<p>#[[ booking_id ]] - Payment For [[ booking_services_name ]] changed to [[ payment_status ]].</p>',

        ]);

        $template = NotificationTemplate::create([
            'type' => 'wallet_payout_transfer',
            'name' => 'wallet_payout_transfer',
            'label' => 'Wallet Payout Transfer',
            'status' => 1,
            'to' => '["admin","provider","handyman"]',
            'channels' => ['PUSH_NOTIFICATION' => '1','IS_MAIL' => '0', 'IS_SMS' => '1', 'IS_WHATSAPP' => '1'],
        ]);
        $template->defaultNotificationTemplateMap()->create([
            'language' => 'en',
            'notification_link' => '',
            'notification_message' => '',
            'user_type' => 'admin',
            'status' => 1,
            'subject' => 'Wallet Payout Transfer',
            'mail_subject' => 'Wallet Payout Transfer',
            'whatsapp_subject' => 'Wallet Payout Transfer',
            'sms_subject' => 'Wallet Payout Transfer',
            'template_detail' => '<p>Payout of [[ pay_amount ]] has been processed.</p>',
            'whatsapp_template_detail' => '<p>You have received a payout of [[ pay_amount ]].</p>',
            'sms_template_detail' => '<p>You have received a payout of [[ pay_amount ]].</p>',
            'mail_template_detail' => '<p>You have received a payout of [[ pay_amount ]].</p>',
        ]);

        $template->defaultNotificationTemplateMap()->create([
            'language' => 'en',
            'notification_link' => '',
            'notification_message' => '',
            'user_type' => 'provider',
            'status' => 1,
            'subject' => 'Payout Received',
            'mail_subject' => 'Payout Received',
            'whatsapp_subject' => 'Payout Received',
            'sms_subject' => 'Payout Received',
            'template_detail' => '<p>You have received a payout of [[ pay_amount ]].</p>',
            'whatsapp_template_detail' => '<p>You have received a payout of [[ pay_amount ]].</p>',
            'sms_template_detail' => '<p>You have received a payout of [[ pay_amount ]].</p>',
            'mail_template_detail' => '<p>You have received a payout of [[ pay_amount ]].</p>',
        ]);

        $template->defaultNotificationTemplateMap()->create([
            'language' => 'en',
            'notification_link' => '',
            'notification_message' => '',
            'user_type' => 'handyman',
            'status' => 1,
            'subject' => 'Payout Received',
            'mail_subject' => 'Payout Received',
            'whatsapp_subject' => 'Payout Received',
            'sms_subject' => 'Payout Received',
            'template_detail' => '<p>You have received a payout of [[ pay_amount ]].</p>',
            'whatsapp_template_detail' => '<p>You have received a payout of [[ pay_amount ]].</p>',
            'sms_template_detail' => '<p>You have received a payout of [[ pay_amount ]].</p>',
            'mail_template_detail' => '<p>You have received a payout of [[ pay_amount ]].</p>',

        ]);

        $template = NotificationTemplate::create([
            'type' => 'wallet_top_up',
            'name' => 'wallet_top_up',
            'label' => 'Wallet Top Up',
            'status' => 1,
            'to' => '["admin","provider","user"]',
            'channels' => ['PUSH_NOTIFICATION' => '1','IS_MAIL' => '0', 'IS_SMS' => '1', 'IS_WHATSAPP' => '1'],
        ]);
        $template->defaultNotificationTemplateMap()->create([
            'language' => 'en',
            'notification_link' => '',
            'notification_message' => '',
            'user_type' => 'admin',
            'status' => 1,
            'subject' => 'Wallet Top-Up',
            'mail_subject' => 'Wallet Top-Up',
            'whatsapp_subject' => 'Wallet Top-Up',
            'sms_subject' => 'Wallet Top-Up',
            'template_detail' => '<p>[[ customer_name ]] has topped up wallet with [[ credit_debit_amount ]].</p>',
            'whatsapp_template_detail' => '<p>[[ customer_name ]] has topped up wallet with [[ credit_debit_amount ]].</p>',
            'sms_template_detail' => '<p>[[ customer_name ]] has topped up wallet with [[ credit_debit_amount ]].</p>',
            'mail_template_detail' => '<p>[[ customer_name ]] has topped up wallet with [[ credit_debit_amount ]].</p>',

        ]);
        $template->defaultNotificationTemplateMap()->create([
            'language' => 'en',
            'notification_link' => '',
            'notification_message' => '',
            'user_type' => 'provider',
            'status' => 1,
            'subject' => 'Wallet Top-Up',
            'mail_subject' => 'Wallet Top-Up',
            'whatsapp_subject' => 'Wallet Top-Up',
            'sms_subject' => 'Wallet Top-Up',
            'template_detail' => '<p>[[ credit_debit_amount ]] has been added to your wallet.</p>',
            'whatsapp_template_detail' => '<p>[[ credit_debit_amount ]] has been added to your wallet.</p>',
            'sms_template_detail' => '<p>[[ credit_debit_amount ]] has been added to your wallet.</p>',
            'mail_template_detail' => '<p>[[ credit_debit_amount ]] has been added to your wallet.</p>',
        ]);
        $template->defaultNotificationTemplateMap()->create([
            'language' => 'en',
            'notification_link' => '',
            'notification_message' => '',
            'user_type' => 'user',
            'status' => 1,
            'subject' => 'Wallet Top-Up',
            'mail_subject' => 'Wallet Top-Up',
            'whatsapp_subject' => 'Wallet Top-Up',
            'sms_subject' => 'Wallet Top-Up',
            'template_detail' => '<p>[[ credit_debit_amount ]] has been added to your wallet.</p>',
            'whatsapp_template_detail' => '<p>[[ credit_debit_amount ]] has been added to your wallet.</p>',
            'sms_template_detail' => '<p>[[ credit_debit_amount ]] has been added to your wallet.</p>',
            'mail_template_detail' => '<p>[[ credit_debit_amount ]] has been added to your wallet.</p>',

        ]);

        $template = NotificationTemplate::create([
            'type' => 'wallet_refund',
            'name' => 'wallet_refund',
            'label' => 'Wallet Refund',
            'status' => 1,
            'to' => '["admin","provider","user"]',
            'channels' => ['PUSH_NOTIFICATION' => '1','IS_MAIL' => '0', 'IS_SMS' => '1', 'IS_WHATSAPP' => '1'],
        ]);
        $template->defaultNotificationTemplateMap()->create([
            'language' => 'en',
            'notification_link' => '',
            'notification_message' => '',
            'user_type' => 'admin',
            'status' => 1,
            'subject' => 'Wallet Refund',
            'mail_subject' => 'Wallet Refund',
            'whatsapp_subject' => 'Wallet Refund',
            'sms_subject' => 'Wallet Refund',
            'template_detail' => '<p>#[[ booking_id ]] - Refund of [[ refund_amount ]] has been processed to the customer.</p>',
            'whatsapp_template_detail' => '<p>#[[ booking_id ]] - Refund of [[ refund_amount ]] has been processed to the customer.</p>',
            'sms_template_detail' => '<p>#[[ booking_id ]] - Refund of [[ refund_amount ]] has been processed to the customer.</p>',
            'mail_template_detail' => '<p>#[[ booking_id ]] - Refund of [[ refund_amount ]] has been processed to the customer.</p>',
        ]);
        $template->defaultNotificationTemplateMap()->create([
            'language' => 'en',
            'notification_link' => '',
            'notification_message' => '',
            'user_type' => 'provider',
            'status' => 1,
            'subject' => 'Wallet Refund',
            'mail_subject' => 'Wallet Refund',
            'whatsapp_subject' => 'Wallet Refund',
            'sms_subject' => 'Wallet Refund',
            'template_detail' => '<p>#[[ booking_id ]] - Refund of [[ refund_amount ]] has been processed to the customer.</p>',
            'whatsapp_template_detail' => '<p>#[[ booking_id ]] - Refund of [[ refund_amount ]] has been processed to the customer.</p>',
            'sms_template_detail' => '<p>#[[ booking_id ]] - Refund of [[ refund_amount ]] has been processed to the customer.</p>',
            'mail_template_detail' => '<p>#[[ booking_id ]] - Refund of [[ refund_amount ]] has been processed to the customer.</p>',
        ]);
        $template->defaultNotificationTemplateMap()->create([
            'language' => 'en',
            'notification_link' => '',
            'notification_message' => '',
            'user_type' => 'user',
            'status' => 1,
            'subject' => 'Wallet Refund',
            'mail_subject' => 'Wallet Refund',
            'whatsapp_subject' => 'Wallet Refund',
            'sms_subject' => 'Wallet Refund',
            'template_detail' => '<p>#[[ booking_id ]] - Refund of [[ refund_amount ]] has been processed to your wallet.</p>',
            'whatsapp_template_detail' => '<p>#[[ booking_id ]] - Refund of [[ refund_amount ]] has been processed to your wallet.</p>',
            'sms_template_detail' => '<p>#[[ booking_id ]] - Refund of [[ refund_amount ]] has been processed to your wallet.</p>',
            'mail_template_detail' => '<p>#[[ booking_id ]] - Refund of [[ refund_amount ]] has been processed to your wallet.</p>',
        ]);

        $template = NotificationTemplate::create([
            'type' => 'paid_with_wallet',
            'name' => 'paid_with_wallet',
            'label' => 'Paid For Booking',
            'status' => 1,
            'to' => '["admin","provider","handyman","user"]',
            'channels' => ['PUSH_NOTIFICATION' => '1','IS_MAIL' => '0', 'IS_SMS' => '1', 'IS_WHATSAPP' => '1'],
        ]);
        $template->defaultNotificationTemplateMap()->create([
            'language' => 'en',
            'notification_link' => '',
            'notification_message' => '',
            'user_type' => 'admin',
            'status' => 1,
            'subject' => 'Payment Paid For Booking',
            'mail_subject' => 'Payment Paid For Booking',
            'whatsapp_subject' => 'Payment Paid For Booking',
            'sms_subject' => 'Payment Paid For Booking',
            'template_detail' => '<p>#[[ booking_id ]] - [[ customer_name ]] has paid payment of [[ amount ]] using wallet.</p>',
            'whatsapp_template_detail' => '<p>#[[ booking_id ]] - [[ customer_name ]] has paid payment of [[ amount ]] using wallet.</p>',
            'sms_template_detail' => '<p>#[[ booking_id ]] - [[ customer_name ]] has paid payment of [[ amount ]] using wallet.</p>',
            'mail_template_detail' => '<p>#[[ booking_id ]] - [[ customer_name ]] has paid payment of [[ amount ]] using wallet.</p>',
        ]);
        $template->defaultNotificationTemplateMap()->create([
            'language' => 'en',
            'notification_link' => '',
            'notification_message' => '',
            'user_type' => 'provider',
            'status' => 1,
            'subject' => 'Payment Paid For Booking',
            'mail_subject' => 'Payment Paid For Booking',
            'whatsapp_subject' => 'Payment Paid For Booking',
            'sms_subject' => 'Payment Paid For Booking',
            'template_detail' => '<p>#[[ booking_id ]] - [[ customer_name ]] has paid payment of [[ amount ]] using wallet.</p>',
            'whatsapp_template_detail' => '<p>#[[ booking_id ]] - [[ customer_name ]] has paid payment of [[ amount ]] using wallet.</p>',
            'sms_template_detail' => '<p>#[[ booking_id ]] - [[ customer_name ]] has paid payment of [[ amount ]] using wallet.</p>',
            'mail_template_detail' => '<p>#[[ booking_id ]] - [[ customer_name ]] has paid payment of [[ amount ]] using wallet.</p>',
        ]);
        $template->defaultNotificationTemplateMap()->create([
            'language' => 'en',
            'notification_link' => '',
            'notification_message' => '',
            'user_type' => 'handyman',
            'status' => 1,
            'subject' => 'Payment Paid For Booking',
            'mail_subject' => 'Payment Paid For Booking',
            'whatsapp_subject' => 'Payment Paid For Booking',
            'sms_subject' => 'Payment Paid For Booking',
            'template_detail' => '<p>#[[ booking_id ]] - [[ customer_name ]] has paid payment of [[ amount ]] using wallet.</p>',
            'whatsapp_template_detail' => '<p>#[[ booking_id ]] - [[ customer_name ]] has paid payment of [[ amount ]] using wallet.</p>',
            'sms_template_detail' => '<p>#[[ booking_id ]] - [[ customer_name ]] has paid payment of [[ amount ]] using wallet.</p>',
            'mail_template_detail' => '<p>#[[ booking_id ]] - [[ customer_name ]] has paid payment of [[ amount ]] using wallet.</p>',

        ]);
        $template->defaultNotificationTemplateMap()->create([
            'language' => 'en',
            'notification_link' => '',
            'notification_message' => '',
            'user_type' => 'user',
            'status' => 1,
            'subject' => 'Payment Paid For Booking',
            'mail_subject' => 'Payment Paid For Booking',
            'whatsapp_subject' => 'Payment Paid For Booking',
            'sms_subject' => 'Payment Paid For Booking',
            'template_detail' => '<p>#[[ booking_id ]] - Payment of [[ amount ]] using wallet paid successfully.</p>',
            'whatsapp_template_detail' => '<p>#[[ booking_id ]] - Payment of [[ amount ]] using wallet paid successfully.</p>',
            'sms_template_detail' => '<p>#[[ booking_id ]] - Payment of [[ amount ]] using wallet paid successfully.</p>',
            'mail_template_detail' => '<p>#[[ booking_id ]] - Payment of [[ amount ]] using wallet paid successfully.</p>',
        ]);

        $template = NotificationTemplate::create([
            'type' => 'job_requested',
            'name' => 'job_requested',
            'label' => 'New Custom Job Request',
            'status' => 1,
            'to' => '["admin","provider"]',
            'channels' => ['PUSH_NOTIFICATION' => '1','IS_MAIL' => '0', 'IS_SMS' => '1', 'IS_WHATSAPP' => '1'],
        ]);

        $template->defaultNotificationTemplateMap()->create([
            'language' => 'en',
            'notification_link' => '',
            'notification_message' => 'New Custom Job Request',
            'status' => 1,
            'user_type' => 'admin',
            'subject' => 'New Custom Job Request',
            'mail_subject' => 'New Custom Job Request',
            'whatsapp_subject' => 'New Custom Job Request',
            'sms_subject' => 'New Custom Job Request',
            'template_detail' => '<p>#[[ job_id ]] - [[ customer_name ]] has requested a new job [[ job_name ]].</p>',
            'whatsapp_template_detail' => '<p>#[[ job_id ]] - [[ customer_name ]] has requested a new job [[ job_name ]].</p>',
            'sms_template_detail' => '<p>#[[ job_id ]] - [[ customer_name ]] has requested a new job [[ job_name ]].</p>',
            'mail_template_detail' => '<p>#[[ job_id ]] - [[ customer_name ]] has requested a new job [[ job_name ]].</p>',
        ]);

        $template->defaultNotificationTemplateMap()->create([
            'language' => 'en',
            'notification_link' => '',
            'notification_message' => 'New Custom Job Request',
            'status' => 1,
            'user_type' => 'provider',
            'subject' => 'New Custom Job Request',
            'mail_subject' => 'New Custom Job Request',
            'whatsapp_subject' => 'New Custom Job Request',
            'sms_subject' => 'New Custom Job Request',
            'template_detail' => '<p>#[[ job_id ]] - [[ customer_name ]] has requested a new job [[ job_name ]].</p>',
            'whatsapp_template_detail' => '<p>#[[ job_id ]] - [[ customer_name ]] has requested a new job [[ job_name ]].</p>',
            'sms_template_detail' => '<p>#[[ job_id ]] - [[ customer_name ]] has requested a new job [[ job_name ]].</p>',
            'mail_template_detail' => '<p>#[[ job_id ]] - [[ customer_name ]] has requested a new job [[ job_name ]].</p>',
        ]);

        $template = NotificationTemplate::create([
            'type' => 'provider_send_bid',
            'name' => 'provider_send_bid',
            'label' => 'Provider Send Bid',
            'status' => 1,
            'to' => '["user"]',
            'channels' => ['PUSH_NOTIFICATION' => '1','IS_MAIL' => '0', 'IS_SMS' => '1', 'IS_WHATSAPP' => '1'],
        ]);
        $template->defaultNotificationTemplateMap()->create([
            'language' => 'en',
            'notification_link' => '',
            'notification_message' => '',
            'user_type' => 'user',
            'status' => 1,
            'subject' => 'New Bid Received',
            'mail_subject' => 'New Bid Received',
            'whatsapp_subject' => 'New Bid Received',
            'sms_subject' => 'New Bid Received',
            'template_detail' => '<p>#[[ job_id ]] - You have received a new bid of [[ bid_amount ]] from [[ provider_name ]] on your job request. Check it out!</p>',
            'whatsapp_template_detail' => '<p>#[[ job_id ]] - You have received a new bid of [[ bid_amount ]] from [[ provider_name ]] on your job request. Check it out!</p>',
            'sms_template_detail' => '<p>#[[ job_id ]] - You have received a new bid of [[ bid_amount ]] from [[ provider_name ]] on your job request. Check it out!</p>',
            'mail_template_detail' => '<p>#[[ job_id ]] - You have received a new bid of [[ bid_amount ]] from [[ provider_name ]] on your job request. Check it out!</p>',

        ]);

        $template = NotificationTemplate::create([
            'type' => 'user_accept_bid',
            'name' => 'user_accept_bid',
            'label' => 'Bid Accepted',
            'status' => 1,
            'to' => '["provider"]',
            'channels' => ['PUSH_NOTIFICATION' => '1','IS_MAIL' => '0', 'IS_SMS' => '1', 'IS_WHATSAPP' => '1'],
        ]);
        $template->defaultNotificationTemplateMap()->create([
            'language' => 'en',
            'notification_link' => '',
            'notification_message' => '',
            'user_type' => 'provider',
            'status' => 1,
            'subject' => 'Job Bid Accepted',
            'mail_subject' => 'Job Bid Accepted',
            'whatsapp_subject' => 'Job Bid Accepted',
            'sms_subject' => 'Job Bid Accepted',
            'template_detail' => '<p>#[[ job_request_id ]] - Your bid of [[ job_price ]] for the job request has been accepted by [[ customer_name ]].</p>',
            'whatsapp_template_detail' => '<p>#[[ job_request_id ]] - Your bid of [[ job_price ]] for the job request has been accepted by [[ customer_name ]].</p>',
            'sms_template_detail' => '<p>#[[ job_request_id ]] - Your bid of [[ job_price ]] for the job request has been accepted by [[ customer_name ]].</p>',
            'mail_template_detail' => '<p>#[[ job_request_id ]] - Your bid of [[ job_price ]] for the job request has been accepted by [[ customer_name ]].</p>',
        ]);

        $template = NotificationTemplate::create([
            'type' => 'provider_payout',
            'name' => 'provider_payout',
            'label' => 'Provider Payout',
            'status' => 1,
            'to' => '["provider","admin"]',
            'channels' => ['PUSH_NOTIFICATION' => '1','IS_MAIL' => '0', 'IS_SMS' => '1', 'IS_WHATSAPP' => '1'],
        ]);
        $template->defaultNotificationTemplateMap()->create([
            'language' => 'en',
            'notification_link' => '',
            'notification_message' => '',
            'user_type' => 'provider',
            'status' => 1,
            'subject' => 'Payout Received',
            'mail_subject' => 'Payout Received',
            'whatsapp_subject' => 'Payout Received',
            'sms_subject' => 'Payout Received',
            'template_detail' => '<p>Your payout of [[ amount ]] has been received.</p>',
            'whatsapp_template_detail' => '<p>Your payout of [[ amount ]] has been received.</p>',
            'sms_template_detail' => '<p>Your payout of [[ amount ]] has been received.</p>',
            'mail_template_detail' => '<p>Your payout of [[ amount ]] has been received.</p>',
        ]);

        $template->defaultNotificationTemplateMap()->create([
            'language' => 'en',
            'notification_link' => '',
            'notification_message' => '',
            'user_type' => 'admin',
            'status' => 1,
            'subject' => 'Payout Processed',
            'mail_subject' => 'Payout Processed',
            'whatsapp_subject' => 'Payout Processed',
            'sms_subject' => 'Payout Processed',
            'template_detail' => '<p>Payout of [[ amount ]] has been processed to [[ provider_name ]].</p>',
            'whatsapp_template_detail' => '<p>Payout of [[ amount ]] has been processed to [[ provider_name ]].</p>',
            'sms_template_detail' => '<p>Payout of [[ amount ]] has been processed to [[ provider_name ]].</p>',
            'mail_template_detail' => '<p>Payout of [[ amount ]] has been processed to [[ provider_name ]].</p>',
        ]);


       
        $template = NotificationTemplate::create([
            'type' => 'subscription_add',
            'name' => 'subscription_add',
            'label' => 'Subscription Add',
            'status' => 1,
            'to' => '["admin","provider"]',
            'channels' => ['PUSH_NOTIFICATION' => '1','IS_MAIL' => '0', 'IS_SMS' => '1', 'IS_WHATSAPP' => '1'],
        ]);
        $template->defaultNotificationTemplateMap()->create([
            'language' => 'en',
            'notification_link' => '',
            'notification_message' => '',
            'user_type' => 'admin',
            'status' => 1,
            'subject' => 'New Subscription Plan Activated',
            'mail_subject' => 'New Subscription Plan Activated',
            'whatsapp_subject' => 'New Subscription Plan Activated',
            'sms_subject' => 'New Subscription Plan Activated',
            'template_detail' => '<p>[[ provider_name ]] has subscribed to a new [[ plan_name ]].</p>',
            'whatsapp_template_detail' => '<p>[[ provider_name ]] has subscribed to a new [[ plan_name ]].</p>',
            'sms_template_detail' => '<p>[[ provider_name ]] has subscribed to a new [[ plan_name ]].</p>',
            'mail_template_detail' => '<p>[[ provider_name ]] has subscribed to a new [[ plan_name ]].</p>',
        ]);
        $template->defaultNotificationTemplateMap()->create([
            'language' => 'en',
            'notification_link' => '',
            'notification_message' => '',
            'user_type' => 'provider',
            'status' => 1,
            'subject' => 'New Subscription Plan Activated',
            'mail_subject' => 'New Subscription Plan Activated',
            'whatsapp_subject' => 'New Subscription Plan Activated',
            'sms_subject' => 'New Subscription Plan Activated',
            'template_detail' => '<p>New plan susbcribed - [[ plan_name ]].</p>',
            'whatsapp_template_detail' => '<p>New plan susbcribed - [[ plan_name ]].</p>',
            'sms_template_detail' => '<p>New plan susbcribed - [[ plan_name ]].</p>',
            'mail_template_detail' => '<p>New plan susbcribed - [[ plan_name ]].</p>',
        ]);


        $template = NotificationTemplate::create([
            'type' => 'register',
            'name' => 'register',
            'label' => 'Register',
            'status' => 1,
            'to' => '["admin","provider","handyman","user"]',
            'channels' => ['PUSH_NOTIFICATION' => '1','IS_MAIL' => '0', 'IS_SMS' => '1', 'IS_WHATSAPP' => '1'],
        ]);
        $template->defaultNotificationTemplateMap()->create([
            'language' => 'en',
            'notification_link' => '',
            'notification_message' => '',
            'user_type' => 'admin',
            'status' => 1,
            'subject' => 'New User Registration',
            'mail_subject' => 'New User Registration',
            'whatsapp_subject' => 'New User Registration',
            'sms_subject' => 'New User Registration',
            'template_detail' => '<p>[[ user_name ]] is now registered with us!</p>',
            'whatsapp_template_detail' => '<p>[[ user_name ]] is now registered with us!</p>',
            'sms_template_detail' => '<p>[[ user_name ]] is now registered with us!</p>',
            'mail_template_detail' => '<p>[[ user_name ]] is now registered with us!</p>',
        ]);
        $template->defaultNotificationTemplateMap()->create([
            'language' => 'en',
            'notification_link' => '',
            'notification_message' => '',
            'user_type' => 'provider',
            'status' => 1,
            'subject' => 'New User Registration',
            'mail_subject' => 'New User Registration',
            'whatsapp_subject' => 'New User Registration',
            'sms_subject' => 'New User Registration',
            'template_detail' => '<p>Welcome aboard! We are happy to have you on our team. Your expertise is going to be invaluable to our customers.</p>',
            'whatsapp_template_detail' => '<p>Welcome aboard! We are happy to have you on our team. Your expertise is going to be invaluable to our customers.</p>',
            'sms_template_detail' => '<p>Welcome aboard! We are happy to have you on our team. Your expertise is going to be invaluable to our customers.</p>',
            'mail_template_detail' => '<p>Welcome aboard! We are happy to have you on our team. Your expertise is going to be invaluable to our customers.</p>',
        ]);
        $template->defaultNotificationTemplateMap()->create([
            'language' => 'en',
            'notification_link' => '',
            'notification_message' => '',
            'user_type' => 'handyman',
            'status' => 1,
            'subject' => 'New User Registration',
            'mail_subject' => 'New User Registration',
            'whatsapp_subject' => 'New User Registration',
            'sms_subject' => 'New User Registration',
            'template_detail' => '<p>Welcome to the team! We are excited to have you on board. Your skills are going to be a huge asset to our customers.</p>',
            'whatsapp_template_detail' => '<p>Welcome to the team! We are excited to have you on board. Your skills are going to be a huge asset to our customers.</p>',
            'sms_template_detail' => '<p>Welcome to the team! We are excited to have you on board. Your skills are going to be a huge asset to our customers.</p>',
            'mail_template_detail' => '<p>Welcome to the team! We are excited to have you on board. Your skills are going to be a huge asset to our customers.</p>',
        ]);
        $template->defaultNotificationTemplateMap()->create([
            'language' => 'en',
            'notification_link' => '',
            'notification_message' => '',
            'user_type' => 'user',
            'status' => 1,
            'subject' => 'New User Registration',
            'mail_subject' => 'New User Registration',
            'whatsapp_subject' => 'New User Registration',
            'sms_subject' => 'New User Registration',
            'template_detail' => '<p>Welcome! We are excited to have you join our community. We are looking forward to seeing how you will use our platform to achieve your goals.</p>',
            'whatsapp_template_detail' => '<p>Welcome! We are excited to have you join our community. We are looking forward to seeing how you will use our platform to achieve your goals.</p>',
            'sms_template_detail' => '<p>Welcome! We are excited to have you join our community. We are looking forward to seeing how you will use our platform to achieve your goals.</p>',
            'mail_template_detail' => '<p>Welcome! We are excited to have you join our community. We are looking forward to seeing how you will use our platform to achieve your goals.</p>',
        ]);

        $template = NotificationTemplate::create([
            'type' => 'withdraw_money',
            'name' => 'withdraw_money',
            'label' => 'Withdraw Money',
            'status' => 1,
            'to' => '["admin","provider","user"]',
            'channels' => ['PUSH_NOTIFICATION' => '1','IS_MAIL' => '0', 'IS_SMS' => '1', 'IS_WHATSAPP' => '1'],
        ]);

        $template->defaultNotificationTemplateMap()->create([
            'language' => 'en',
            'notification_link' => '',
            'notification_message' => '',
            'user_type' => 'admin',
            'status' => 1,
            'subject' => 'Money Withdrawn',
            'mail_subject' => 'Money Withdrawn',
            'whatsapp_subject' => 'Money Withdrawn',
            'sms_subject' => 'Money Withdrawn',
            'template_detail' => '<p>[[ user_name ]] has withdrawn [[ amount ]] from the wallet.</p>',
            'whatsapp_template_detail' => '<p>[[ user_name ]] has withdrawn [[ amount ]] from the wallet.</p>',
            'sms_template_detail' => '<p>[[ user_name ]] has withdrawn [[ amount ]] from the wallet.</p>',
            'mail_template_detail' => '<p>[[ user_name ]] has withdrawn [[ amount ]] from the wallet.</p>',

        ]);

        $template->defaultNotificationTemplateMap()->create([
            'language' => 'en',
            'notification_link' => '',
            'notification_message' => '',
            'user_type' => 'provider',
            'status' => 1,
            'subject' => 'Money Withdrawn',
            'mail_subject' => 'Money Withdrawn',
            'whatsapp_subject' => 'Money Withdrawn',
            'sms_subject' => 'Money Withdrawn',
            'template_detail' => '<p>You have withdrawn [[ amount ]] from the wallet.</p>',
            'whatsapp_template_detail' => '<p>You have withdrawn [[ amount ]] from the wallet.</p>',
            'sms_template_detail' => '<p>You have withdrawn [[ amount ]] from the wallet.</p>',
            'mail_template_detail' => '<p>You have withdrawn [[ amount ]] from the wallet.</p>',
        ]);

        $template->defaultNotificationTemplateMap()->create([
            'language' => 'en',
            'notification_link' => '',
            'notification_message' => '',
            'user_type' => 'user',
            'status' => 1,
            'subject' => 'Money Withdrawn',
            'mail_subject' => 'Money Withdrawn',
            'whatsapp_subject' => 'Money Withdrawn',
            'sms_subject' => 'Money Withdrawn',
            'template_detail' => '<p>You have withdrawn [[ amount ]] from the wallet.</p>',
            'whatsapp_template_detail' => '<p>You have withdrawn [[ amount ]] from the wallet.</p>',
            'sms_template_detail' => '<p>You have withdrawn [[ amount ]] from the wallet.</p>',
            'mail_template_detail' => '<p>You have withdrawn [[ amount ]] from the wallet.</p>',

        ]);
        $template = NotificationTemplate::create([
            'type' => 'handyman_payout',
            'name' => 'handyman_payout',
            'label' => 'Handyman Payout',
            'status' => 1,
            'to' => '["provider","handyman"]',
            'channels' => ['PUSH_NOTIFICATION' => '1','IS_MAIL' => '0', 'IS_SMS' => '1', 'IS_WHATSAPP' => '1'],
        ]);
        $template->defaultNotificationTemplateMap()->create([
            'language' => 'en',
            'notification_link' => '',
            'notification_message' => '',
            'user_type' => 'handyman',
            'status' => 1,
            'subject' => 'Payout Received',
            'mail_subject' => 'Payout Received',
            'whatsapp_subject' => 'Payout Received',
            'sms_subject' => 'Payout Received',
            'template_detail' => '<p>Your payout of [[ amount ]] has been received from [[ provider_name ]].</p>',
            'whatsapp_template_detail' => '<p>Your payout of [[ amount ]] has been received from [[ provider_name ]].</p>',
            'sms_template_detail' => '<p>Your payout of [[ amount ]] has been received from [[ provider_name ]].</p>',
            'mail_template_detail' => '<p>Your payout of [[ amount ]] has been received from [[ provider_name ]].</p>',
        ]);

        $template->defaultNotificationTemplateMap()->create([
            'language' => 'en',
            'notification_link' => '',
            'notification_message' => '',
            'user_type' => 'provider',
            'status' => 1,
            'subject' => 'Payout Processed',
            'mail_subject' => 'Payout Processed',
            'whatsapp_subject' => 'Payout Processed',
            'sms_subject' => 'Payout Processed',
            'template_detail' => '<p>Payout of [[ amount ]] has been processed to [[ handyman_name ]].</p>',
            'whatsapp_template_detail' => '<p>Payout of [[ amount ]] has been processed to [[ handyman_name ]].</p>',
            'sms_template_detail' => '<p>Payout of [[ amount ]] has been processed to [[ handyman_name ]].</p>',
            'mail_template_detail' => '<p>Payout of [[ amount ]] has been processed to [[ handyman_name ]].</p>',
        ]);

        $template = NotificationTemplate::create([
            'type' => 'add_helpdesk',
            'name' => 'add_helpdesk',
            'label' => 'Query confirmation',
            'status' => 1,
            'to' => '["admin"]',
            'channels' => ['PUSH_NOTIFICATION' => '1','IS_MAIL' => '0', 'IS_SMS' => '1', 'IS_WHATSAPP' => '1'],
        ]);
        $template->defaultNotificationTemplateMap()->create([
            'language' => 'en',
            'notification_link' => '',
            'notification_message' => '',
            'user_type' => 'admin',
            'status' => 1,
            'subject' => 'New Query Received',
            'mail_subject' => 'New Query Received',
            'whatsapp_subject' => 'New Query Received',
            'sms_subject' => 'New Query Received',
            'template_detail' => '<p>New Query [[ sender_name ]] - [[ subject ]].</p>',
            'whatsapp_template_detail' => '<p>New Query [[ sender_name ]] - [[ subject ]].</p>',
            'sms_template_detail' => '<p>New Query [[ sender_name ]] - [[ subject ]].</p>',
            'mail_template_detail' => '<p>New Query [[ sender_name ]] - [[ subject ]].</p>',
        ]);

        $template = NotificationTemplate::create([
            'type' => 'closed_helpdesk',
            'name' => 'closed_helpdesk',
            'label' => 'Closed',
            'status' => 1,
            'to' => '["admin","provider","handyman","user"]',
            'channels' => ['PUSH_NOTIFICATION' => '1','IS_MAIL' => '0', 'IS_SMS' => '1', 'IS_WHATSAPP' => '1'],
        ]);
        $template->defaultNotificationTemplateMap()->create([
            'language' => 'en',
            'notification_link' => '',
            'notification_message' => '',
            'user_type' => 'admin',
            'status' => 1,
            'subject' => 'Query Closed',
            'mail_subject' => 'Query Closed',
            'whatsapp_subject' => 'Query Closed',
            'sms_subject' => 'Query Closed',
            'template_detail' => '<p>#[[ helpdesk_id ]] closed by [[ sender_name ]].</p>',
            'whatsapp_template_detail' => '<p>#[[ helpdesk_id ]] closed by [[ sender_name ]].</p>',
            'sms_template_detail' => '<p>#[[ helpdesk_id ]] closed by [[ sender_name ]].</p>',
            'mail_template_detail' => '<p>#[[ helpdesk_id ]] closed by [[ sender_name ]].</p>',
        ]);
        $template->defaultNotificationTemplateMap()->create([
            'language' => 'en',
            'notification_link' => '',
            'notification_message' => '',
            'user_type' => 'provider',
            'status' => 1,
            'subject' => 'Query Closed',
            'mail_subject' => 'Query Closed',
            'whatsapp_subject' => 'Query Closed',
            'sms_subject' => 'Query Closed',
            'template_detail' => '<p>#[[ helpdesk_id ]] closed by [[ sender_name ]].</p>',
            'whatsapp_template_detail' => '<p>#[[ helpdesk_id ]] closed by [[ sender_name ]].</p>',
            'sms_template_detail' => '<p>#[[ helpdesk_id ]] closed by [[ sender_name ]].</p>',
            'mail_template_detail' => '<p>#[[ helpdesk_id ]] closed by [[ sender_name ]].</p>',
        ]);
        $template->defaultNotificationTemplateMap()->create([
            'language' => 'en',
            'notification_link' => '',
            'notification_message' => '',
            'user_type' => 'handyman',
            'status' => 1,
            'subject' => 'Query Closed',
            'mail_subject' => 'Query Closed',
            'whatsapp_subject' => 'Query Closed',
            'sms_subject' => 'Query Closed',
            'template_detail' => '<p>#[[ helpdesk_id ]] closed by [[ sender_name ]].</p>',
            'whatsapp_template_detail' => '<p>#[[ helpdesk_id ]] closed by [[ sender_name ]].</p>',
            'sms_template_detail' => '<p>#[[ helpdesk_id ]] closed by [[ sender_name ]].</p>',
            'mail_template_detail' => '<p>#[[ helpdesk_id ]] closed by [[ sender_name ]].</p>',
        ]);
        $template->defaultNotificationTemplateMap()->create([
            'language' => 'en',
            'notification_link' => '',
            'notification_message' => '',
            'user_type' => 'user',
            'status' => 1,
            'subject' => 'Query Closed',
            'mail_subject' => 'Query Closed',
            'whatsapp_subject' => 'Query Closed',
            'sms_subject' => 'Query Closed',
            'template_detail' => '<p>#[[ helpdesk_id ]] closed by [[ sender_name ]].</p>',
            'whatsapp_template_detail' => '<p>#[[ helpdesk_id ]] closed by [[ sender_name ]].</p>',
            'sms_template_detail' => '<p>#[[ helpdesk_id ]] closed by [[ sender_name ]].</p>',
            'mail_template_detail' => '<p>#[[ helpdesk_id ]] closed by [[ sender_name ]].</p>',
        ]);

        $template = NotificationTemplate::create([
            'type' => 'reply_helpdesk',
            'name' => 'reply_helpdesk',
            'label' => 'Replied Query',
            'status' => 1,
            'to' => '["admin","provider","handyman","user"]',
            'channels' => ['PUSH_NOTIFICATION' => '1','IS_MAIL' => '0', 'IS_SMS' => '1', 'IS_WHATSAPP' => '1'],
        ]);
        $template->defaultNotificationTemplateMap()->create([
            'language' => 'en',
            'notification_link' => '',
            'notification_message' => '',
            'user_type' => 'admin',
            'status' => 1,
            'subject' => 'Query Replied',
            'mail_subject' => 'Query Replied',
            'whatsapp_subject' => 'Query Replied',
            'sms_subject' => 'Query Replied',
            'template_detail' => '<p>#[[ helpdesk_id ]] replied by [[ sender_name ]].</p>',
            'whatsapp_template_detail' => '<p>#[[ helpdesk_id ]] replied by [[ sender_name ]].</p>',
            'sms_template_detail' => '<p>#[[ helpdesk_id ]] replied by [[ sender_name ]].</p>',
            'mail_template_detail' => '<p>#[[ helpdesk_id ]] replied by [[ sender_name ]].</p>',
        ]);
        $template->defaultNotificationTemplateMap()->create([
            'language' => 'en',
            'notification_link' => '',
            'notification_message' => '',
            'user_type' => 'provider',
            'status' => 1,
            'subject' => 'Query Replied',
            'mail_subject' => 'Query Replied',
            'whatsapp_subject' => 'Query Replied',
            'sms_subject' => 'Query Replied',
            'template_detail' => '<p>#[[ helpdesk_id ]] replied by [[ sender_name ]].</p>',
            'whatsapp_template_detail' => '<p>#[[ helpdesk_id ]] replied by [[ sender_name ]].</p>',
            'sms_template_detail' => '<p>#[[ helpdesk_id ]] replied by [[ sender_name ]].</p>',
            'mail_template_detail' => '<p>#[[ helpdesk_id ]] replied by [[ sender_name ]].</p>',
        ]);
        $template->defaultNotificationTemplateMap()->create([
            'language' => 'en',
            'notification_link' => '',
            'notification_message' => '',
            'user_type' => 'handyman',
            'status' => 1,
            'subject' => 'Query Replied',
            'mail_subject' => 'Query Replied',
            'whatsapp_subject' => 'Query Replied',
            'sms_subject' => 'Query Replied',
            'template_detail' => '<p>#[[ helpdesk_id ]] replied by [[ sender_name ]].</p>',
            'whatsapp_template_detail' => '<p>#[[ helpdesk_id ]] replied by [[ sender_name ]].</p>',
            'sms_template_detail' => '<p>#[[ helpdesk_id ]] replied by [[ sender_name ]].</p>',
            'mail_template_detail' => '<p>#[[ helpdesk_id ]] replied by [[ sender_name ]].</p>',
        ]);
        $template->defaultNotificationTemplateMap()->create([
            'language' => 'en',
            'notification_link' => '',
            'notification_message' => '',
            'user_type' => 'user',
            'status' => 1,
            'subject' => 'Query Replied',
            'mail_subject' => 'Query Replied',
            'whatsapp_subject' => 'Query Replied',
            'sms_subject' => 'Query Replied',
            'template_detail' => '<p>#[[ helpdesk_id ]] replied by [[ sender_name ]].</p>',
            'whatsapp_template_detail' => '<p>#[[ helpdesk_id ]] replied by [[ sender_name ]].</p>',
            'sms_template_detail' => '<p>#[[ helpdesk_id ]] replied by [[ sender_name ]].</p>',
            'mail_template_detail' => '<p>#[[ helpdesk_id ]] replied by [[ sender_name ]].</p>',
        ]);

        $template = NotificationTemplate::create([
            'type' => 'cancellation_charges',
            'name' => 'cancellation_charges',
            'label' => 'Cancellation Charges',
            'status' => 1,
            'to' => '["admin","user"]',
            'channels' => ['PUSH_NOTIFICATION' => '1','IS_MAIL' => '0', 'IS_SMS' => '1', 'IS_WHATSAPP' => '1'],
        ]);
        $template->defaultNotificationTemplateMap()->create([
            'language' => 'en',
            'notification_link' => '',
            'notification_message' => '',
            'user_type' => 'admin',
            'status' => 1,
            'subject' => 'Cancellation Charges',
            'mail_subject' => 'Cancellation Charges',
            'whatsapp_subject' => 'Cancellation Charges',
            'sms_subject' => 'Cancellation Charges',
            'template_detail' => `<p>#[[ booking_id ]] - A cancellation charge of [[ paid_amount ]] has been deducted from the customer's wallet.</p>`,
            'whatsapp_template_detail' => `<p>#[[ booking_id ]] - A cancellation charge of [[ paid_amount ]] has been deducted from the customer's wallet.</p>`,
            'sms_template_detail' => `<p>#[[ booking_id ]] - A cancellation charge of [[ paid_amount ]] has been deducted from the customer's wallet.</p>`,
            'mail_template_detail' => `<p>#[[ booking_id ]] - A cancellation charge of [[ paid_amount ]] has been deducted from the customer's wallet.</p>`,
        ]);
       
        $template->defaultNotificationTemplateMap()->create([
            'language' => 'en',
            'notification_link' => '',
            'notification_message' => '',
            'user_type' => 'user',
            'status' => 1,
            'subject' => 'Cancellation Charges',
            'mail_subject' => 'Cancellation Charges',
            'whatsapp_subject' => 'Cancellation Charges',
            'sms_subject' => 'Cancellation Charges',
            'template_detail' => '<p>#[[ booking_id ]] - A cancellation charge [[ paid_amount ]] has been deducted from your wallet.</p>',
            'whatsapp_template_detail' => '<p>#[[ booking_id ]] - A cancellation charge [[ paid_amount ]] has been deducted from your wallet.</p>',
            'sms_template_detail' => '<p>#[[ booking_id ]] - A cancellation charge [[ paid_amount ]] has been deducted from your wallet.</p>',
            'mail_template_detail' => '<p>#[[ booking_id ]] - A cancellation charge [[ paid_amount ]] has been deducted from your wallet.</p>',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notification_template_content_mapping', function (Blueprint $table) {
            $table->dropColumn('mail_template_detail');
            $table->dropColumn('mail_subject');
            $table->dropColumn('sms_template_detail');
            $table->dropColumn('sms_subject');
            $table->dropColumn('whatsapp_template_detail');
            $table->dropColumn('whatsapp_subject');
        });
    }
};
