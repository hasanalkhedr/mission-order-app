<?php
use App\Notifications\MemoireMissionOrderApproveNotification;
use App\Notifications\MemoireMissionOrderLevelNotification;
use App\Notifications\MemoireTourneeApproveNotification;
use App\Notifications\MemoireTourneeLevelNotification;
use App\Notifications\MissionOrderApproveNotification;
use App\Notifications\MissionOrderLevelNotification;
use App\Notifications\SignatureApproveNotification;
use App\Notifications\SignatureNotification;
use App\Notifications\TourneeApproveNotification;
use App\Notifications\TourneeLevelNotification;

return [
    /*
     * Model that has the "Notifiable" and "HasMegaphone" Traits
     */
    'model' => \App\Models\User::class,

    /*
     * Array of all the notification types to display in Megaphone
     */
    'types' => [
        \MBarlow\Megaphone\Types\General::class,
        \MBarlow\Megaphone\Types\NewFeature::class,
        \MBarlow\Megaphone\Types\Important::class,
    ],

    /*
     * Custom notification types specific to your App
     */
    'customTypes' => [
        /*
            Associative array in the format of
            \Namespace\To\Notification::class => 'path.to.view',
         */
        MissionOrderApproveNotification::class => 'vendor.megaphone.types.mission-order-approve-notification',
        MissionOrderLevelNotification::class => 'vendor.megaphone.types.mission-order-level-notification',
        MemoireMissionOrderApproveNotification::class => 'vendor.megaphone.types.memoire-mission-order-approve-notification',
        MemoireMissionOrderLevelNotification::class => 'vendor.megaphone.types.memoire-mission-order-level-notification',

        TourneeApproveNotification::class => 'vendor.megaphone.types.tournee-approve-notification',
        TourneeLevelNotification::class => 'vendor.megaphone.types.tournee-level-notification',
        MemoireTourneeApproveNotification::class => 'vendor.megaphone.types.memoire-tournee-approve-notification',
        MemoireTourneeLevelNotification::class => 'vendor.megaphone.types.memoire-tournee-level-notification',

        SignatureNotification::class => 'vendor.megaphone.types.signature-notification',
        SignatureApproveNotification::class => 'vendor.megaphone.types.signature-approve-notification',
    ],

    /*
     * Array of Notification types available within MegaphoneAdmin Component or
     * leave as null to show all types / customTypes
     *
     * 'adminTypeList' => [
     *     \MBarlow\Megaphone\Types\NewFeature::class,
     *     \MBarlow\Megaphone\Types\Important::class,
     * ],
     */
    'adminTypeList' => null,

    /*
     * Clear Megaphone notifications older than....
     */
    'clearAfter' => '2 weeks',

    /*
     * Option for setting the icon to show actual count of unread Notifications or
     * show a dot instead
     */
    'showCount' => true,

    /*
     * Enable Livewire Poll feature for auto updating.
     * See livewire docs for poll option descriptions
     * @link https://livewire.laravel.com/docs/wire-poll
     */
    'poll' => [
        'enabled' => false,

        'options' => [
            'time' => '15s',
            'keepAlive' => false,
            'viewportVisible' => false,
        ],
    ],
];
