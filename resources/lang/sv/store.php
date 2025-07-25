<?php

// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

return [
    'cart' => [
        'checkout' => 'Checka ut',
        'empty_cart' => 'Ta bort alla artiklar från varukorgen',
        'info' => ':count_delimited föremål i varukorgen ($:subtotal)|:count_delimited föremål i varukorgen ($:subtotal)',
        'more_goodies' => 'Jag vill ta en titt på fler godsaker innan jag fullbordar beställningen',
        'shipping_fees' => 'fraktavgifter',
        'title' => 'Varukorg',
        'total' => 'totalt',

        'errors_no_checkout' => [
            'line_1' => 'Oj då, det är problem med din varukorg som förhindrar en utcheckning!',
            'line_2' => 'Ta bort eller uppdatera produkterna ovan för att fortsätta.',
        ],

        'empty' => [
            'text' => 'Din varukorg är tom.',
            'return_link' => [
                '_' => 'Återvänd till :link för att hitta några godsaker!',
                'link_text' => 'butikslista',
            ],
        ],
    ],

    'checkout' => [
        'cart_problems' => 'Oj då, det finns problem med din varukorg!',
        'cart_problems_edit' => 'Klicka här för att redigera den.',
        'declined' => 'Betalningen avbröts.',
        'delayed_shipping' => 'Vi är för nuvarande överväldigade med ordrar! Du får gärna placera din order, men kommer kanske få en **extra 1-2 veckors försening** medan vi kommer ikapp alla ordrar.',
        'hide_from_activity' => 'Dölj alla osu!supporter taggar i denna ordning från min aktivitet',
        'old_cart' => 'Din varukorg verkar vara inaktuell och har blivit återladdad, var god försök igen.',
        'pay' => 'Betala med Paypal',
        'title_compact' => 'kassan',

        'has_pending' => [
            '_' => 'Du har ofullbordade transaktioner, klicka :link för att se dem.',
            'link_text' => 'här',
        ],

        'pending_checkout' => [
            'line_1' => 'En tidigare transaktion startades men avslutades inte.',
            'line_2' => 'Välj en betalmetod för att återuppta din tidigare transaktion.',
        ],
    ],

    'discount' => 'spara :percent%',
    'free' => 'gratis!',

    'invoice' => [
        'contact' => 'Kontakt:',
        'date' => 'Datum:',
        'echeck_delay' => 'Eftersom din betalning var en eCheck, vänligen tillåt upp till 10 extra dagar för betalningen att accepteras via PayPal! ',
        'echeck_denied' => 'eCheck-betalningen avvisades av PayPal.',
        'hide_from_activity' => 'osu!supporter taggar i denna ordning visas inte i dina senaste aktiviteter.',
        'sent_via' => 'Skickat Via:',
        'shipping_to' => 'Levereras Till:',
        'title' => 'Faktura',
        'title_compact' => 'faktura',

        'status' => [
            'cancelled' => [
                'title' => 'Din beställning har avbrutits',
                'line_1' => [
                    '_' => "Om du inte bad om en avbrytning var god kontakta :link med ditt order nummer (#:order_number).",
                    'link_text' => 'osu!store support',
                ],
            ],
            'delivered' => [
                'title' => 'Din beställning har levererats! Vi hoppas att du tycker om den!',
                'line_1' => [
                    '_' => 'Om du har några problem med ditt köp, vänligen kontakta :link.',
                    'link_text' => 'osu!store support',
                ],
            ],
            'prepared' => [
                'title' => 'Din beställning förbereds!',
                'line_1' => 'Vänligen vänta lite längre på att den ska skickas. Spårningsinformation kommer att visas här när beställningen har behandlats och skickats. Detta kan ta upp till 5 dagar (men oftast mindre!) beroende på hur upptagna vi är.',
                'line_2' => 'Vi skickas alla beställningar från Japan med hjälp av en mängd olika frakttjänster beroende på vikt och värde. Detta fält kommer att uppdateras med detaljer när vi har levererat beställningen.',
            ],
            'processing' => [
                'title' => 'Din betalning har ännu inte bekräftats!',
                'line_1' => 'Om du redan har betalat, kan vi fortfarande vänta på att få bekräftelse på din betalning. Vänligen uppdatera denna sida om en minut eller två!',
                'line_2' => [
                    '_' => 'Om du stötte på ett problem i kassan, :link',
                    'link_text' => 'klicka här för att återuppta din transaktion',
                ],
            ],
            'shipped' => [
                'title' => 'Din beställning har skickats!',
                'tracking_details' => 'Spårningsinformation följer:',
                'no_tracking_details' => [
                    '_' => "Vi har inga spårningsuppgifter eftersom vi skickade paketet via Air Mail, men vi uppskattar att du kommer få det inom 1-3 veckor. I Europa kan tullen förlänga väntetiden, vilket är utom vår kontroll. Om du har några funderingar kan du svara på bekräftelse mejlet du fick av oss (eller :link).",
                    'link_text' => 'skicka oss ett e-post',
                ],
            ],
        ],
    ],

    'order' => [
        'cancel' => 'Avbryt beställning',
        'cancel_confirm' => 'Denna beställning kommer att avbrytas och betalning kommer inte godtas. Betaltjänsten kanske inte frigör reserverade pengar direkt. Är du säker?',
        'cancel_not_allowed' => 'Denna beställning kan för tillfället inte avbrytas.',
        'invoice' => 'Visa faktura',
        'no_orders' => 'Inga beställningar att visa.',
        'paid_on' => 'Beställning slutförd :date',
        'resume' => 'Återuppta transaktionen',
        'shipping_and_handling' => 'Frakt & Hantering',
        'shopify_expired' => 'Kassalänken för denna beställning har utgått.',
        'subtotal' => 'Delsumma',
        'total' => 'Summa',

        'details' => [
            'order_number' => 'Beställning #',
            'payment_terms' => 'Betalningsvillkor',
            'salesperson' => 'Försäljare',
            'shipping_method' => 'Leveransmetod',
            'shipping_terms' => 'Leveransvillkor',
            'title' => 'Beställningsinformation',
        ],

        'item' => [
            'quantity' => 'Antal',

            'display_name' => [
                'supporter_tag' => ':name till :username (:duration)',
            ],

            'subtext' => [
                'supporter_tag' => 'Meddelande: :message',
            ],
        ],

        'not_modifiable_exception' => [
            'cancelled' => 'Du kan inte ändra din beställning då den har blivit avbruten.',
            'checkout' => 'Du kan inte ändra din beställning när den bearbetas.', // checkout and processing should have the same message.
            'default' => 'Beställningen kan inte ändras',
            'delivered' => 'Du kan inte ändra din beställning då den redan har blivit levererad.',
            'paid' => 'Du kan inte ändra din beställning då den redan har betalats.',
            'processing' => 'Du kan inte ändra din beställning när den bearbetas.',
            'shipped' => 'Du kan inte ändra din beställning då den redan har skickats.',
        ],

        'status' => [
            'cancelled' => 'Avbruten',
            'checkout' => 'Förbereder',
            'delivered' => 'Levererad',
            'paid' => 'Betalt',
            'processing' => 'Väntar på bekräftelse',
            'shipped' => 'Skickad',
            'title' => 'Beställningsstatus',
        ],

        'thanks' => [
            'title' => 'Tack för din beställning!',
            'line_1' => [
                '_' => 'Du kommer att få ett bekräftelsemail snart. Om du har några frågor, vänligen :link!',
                'link_text' => 'kontakta oss',
            ],
        ],
    ],

    'product' => [
        'name' => 'Namn',

        'stock' => [
            'out' => 'Detta föremål är för närvarande slut. Kom tillbaka senare!',
            'out_with_alternative' => 'Tyvärr är denna artikel slut i lager. Använd rullgardinsmenyn för att välja en annan typ eller kom tillbaka senare!',
        ],

        'add_to_cart' => 'Lägg till i varukorgen',
        'notify' => 'Notifiera mig när den är tillgänglig!',
        'out_of_stock' => '',

        'notification_success' => 'du kommer bli notifierad när vi har mer i lager. klicka :link för att avbryta',
        'notification_remove_text' => 'här',

        'notification_in_stock' => 'Denna produkt är redan i lager!',
    ],

    'supporter_tag' => [
        'gift' => 'ge som gåva',
        'gift_message' => 'lägg till ett valfritt meddelande till din gåva! (upp till :length tecken)',

        'require_login' => [
            '_' => 'Du behöver vara :link för att kunna få en osu!supporter tagg!',
            'link_text' => 'inloggad',
        ],
    ],

    'username_change' => [
        'check' => 'Skriv in ett användarnamn för att kontrollera tillgänglighet!',
        'checking' => 'Kontrollerar om :username är tillgängligt...',
        'placeholder' => 'Begärt Användarnamn',
        'label' => 'Nytt Användarnamn',
        'current' => 'Ditt nuvarande användarnamn är ":username".',

        'require_login' => [
            '_' => 'Du behöver var :link för att ändra ditt namn!',
            'link_text' => 'inloggad',
        ],
    ],

    'xsolla' => [
        'distributor' => '',
    ],
];
