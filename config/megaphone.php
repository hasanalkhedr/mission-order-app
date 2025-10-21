<?php
use App\Notifications\ChancelleryRateApproveNotification;
use App\Notifications\ChancelleryRateMissingNotification;
use App\Notifications\ChancelleryRateNotification;
use App\Notifications\MemoireMissionOrderAccountantNotification;
use App\Notifications\MemoireMissionOrderApproveNotification;
use App\Notifications\MemoireMissionOrderLevelNotification;
use App\Notifications\MemoireMissionOrderReadyToPayAccountantNotification;
use App\Notifications\MemoireMissionOrderReadyToPayNotification;
use App\Notifications\MemoireMissionOrderRejectAccountantNotification;
use App\Notifications\MemoireMissionOrderRejectNotification;
use App\Notifications\MemoireTourneeAccountantNotification;
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
        ChancelleryRateNotification::class => 'vendor.megaphone.types.chancelleryRate-notification',
        ChancelleryRateApproveNotification::class => 'vendor.megaphone.types.chancelleryRate-approve-notification',
        ChancelleryRateMissingNotification::class => 'vendor.megaphone.types.chancelleryRate-missing-notification',

        MemoireMissionOrderAccountantNotification::class => 'vendor.megaphone.types.memoire-mission-order-accountant-notification',
        MemoireTourneeAccountantNotification::class => 'vendor.megaphone.types.memoire-tournee-accountant-notification',

        MemoireMissionOrderReadyToPayNotification::class => 'vendor.megaphone.types.memoire-mission-order-readyToPay-notification',
        MemoireMissionOrderReadyToPayAccountantNotification::class => 'vendor.megaphone.types.memoire-mission-order-readyToPay-accountant-notification',
        MemoireMissionOrderRejectNotification::class => 'vendor.megaphone.types.memoire-mission-order-reject-notification',
        MemoireMissionOrderRejectAccountantNotification::class => 'vendor.megaphone.types.memoire-mission-order-reject-accountant-notification',

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
