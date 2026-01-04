<?php

declare(strict_types=1);

return [

    'default' => env('FIREBASE_PROJECT', 'fsbox'),

    'projects' => [
        'fsbox' => [
            'credentials' => [
                "type" => "service_account",
                "project_id" => "fsbox-35138",
                "private_key_id" => "9980955abb6288706ef982438ce70e00aa91703c",
                "private_key" => "-----BEGIN PRIVATE KEY-----\nMIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQCvGUIj51Ei8UrM\nY09GXCkrt3jW3l0TO8563hhzFgGjgUj3qnrga5djIGaC2LhEehTpeS2Yn2XgBkT7\nyue61CL4M68nRIlsqGZ+gP65Hfmp0X08cyBBGwNvF9ktCFkv21P0SWsRNnJj0S77\nrsrKHf8cZ3KFJ+ukM9wW611V3NDbHkE7MPUtJEyIYHKJSEbCo7SPO3EuBCJuakQn\naqpa9r/RDq5Yo4E7tJL3DAlFyTXUeT/sEOlV8HX5WXZWfmtoLwYvSpQpzA+LeZgc\nxJWLIFVDw9vhNZqJcZeObJtb9P618+uPGbYmpCPTaMlRgrELa9fNCkCl6I0YFtt8\nRD0MpQLxAgMBAAECggEAQsvm8F1pFWRpPWlRL60uw4+dWJLBfPnevf6F4zls7JwA\nYwK1F3HT8avFj3rvaKgN3DcvDr8YFQsvO8Le/eW5ZWgKFHfP2RSw7Od2xNXLjV3u\nYaYlktCkUbgOUGCT7W7FKzbq0kksAConuzmmAM7KqGJKTMO64AwslM268GtpfXz+\nUb47VGW6namHpPmFPS4JgX+iVragIx6KPeGGBzHdPDTH5S16LTCGG0eDk3kMzVcW\n3lUtOuT1HWHwUpdYCWAMohTwlA5amfK2M0KH4Zkh/Gwmbn062maMHIqmFQcqbG96\nLOnOXCKR1U16uBZJ9cCg33qsbMpqjR0EouUmo4M/RQKBgQDiCeYcegOa8DHCsxwy\nS/55obH2Uv6GZz87S/olbEl+r05wDAkGfjndVePf09tj9xpfBzkB3fU36E0qvILv\neMHUhlPP/Mavudcvv7aEtIvaUG17eUNcM57mthWb9Mc0vzSa1kreCX4ahis32lxp\nxGtiHydv8f9T2UxUtyohCP1uZwKBgQDGTtP0L8NucHDnNQeyTLDlc9kqgx2Ket5Y\nu18c5qZo8/BZEOHgYbm17bED9+B8KmTYldWQVDure753mxd2ZgVFtTHClBqIZTGl\n1QEXPJOQHs2WX1P3dejnzNAAtO+O7Sygfps5P0nAmhh9jcoHi7QTgD4XiRc3YvD3\n6xUy/s/85wKBgGtqmNffg+cVThBgXX3pbz0OAiw0tI/acMoVCQLPuv1hAb1teryD\nL6xnLKspWpDe1MFkBUtF10qCMmmku/RhRntgemPUk/beMcyXJn1Z7zwIDH8o2UHv\npgutbAd5A1Glq7IJM/rgN/US3WbldhUtKnquo2cncVa/ZO69PGAGjajxAoGARbB4\nDe0nGc2L78SUEtWeqNQAck8nd33cW4RlVWu8+U8YnMQxDMBrGgMQ3RskGZ5wWCi3\np4PigE35TmEDaslJewjFFuMjO+GMBAIJ4xeXp+MbTofroyAdnDA+vLDZKvowX6p+\ndCzIh1Xf22eTovk2bE/6Ah1cOkub8RP9HDGt9+sCgYAQLTEJNCzyY8P4RUWIpjIT\noYwPc3WimRd8O9JreddbQkeDwCz4va8UWPiL7/11+LVljqXbQbeSBaiDhasAMh1B\nfQj04J8I06eAnzO3VxCz+Sbz0ohV1KrOeYxsbNb1fzKzOylMNIOYBkZ5QY3G+j8Y\nASecejfuDiqyT/WK5mTC9Q==\n-----END PRIVATE KEY-----\n",
                "client_email" => "firebase-adminsdk-1p41o@fsbox-35138.iam.gserviceaccount.com",
                "client_id" => "109303123504150721549",
                "auth_uri" => "https://accounts.google.com/o/oauth2/auth",
                "token_uri" => "https://oauth2.googleapis.com/token",
                "auth_provider_x509_cert_url" => "https://www.googleapis.com/oauth2/v1/certs",
                "client_x509_cert_url" => "https://www.googleapis.com/robot/v1/metadata/x509/firebase-adminsdk-1p41o%40fsbox-35138.iam.gserviceaccount.com",
                "universe_domain" => "googleapis.com"
            ],

            'auth' => [
                'tenant_id' => env('FIREBASE_AUTH_TENANT_ID'),
            ],

            'firestore' => [
            ],

            'database' => [
                'url' => env('FIREBASE_DATABASE_URL'),
            ],

            'dynamic_links' => [
                'default_domain' => env('FIREBASE_DYNAMIC_LINKS_DEFAULT_DOMAIN'),
            ],

            'storage' => [
                'default_bucket' => env('FIREBASE_STORAGE_DEFAULT_BUCKET'),
            ],

            'cache_store' => env('FIREBASE_CACHE_STORE', 'file'),

            'logging' => [
                'http_log_channel' => env('FIREBASE_HTTP_LOG_CHANNEL'),
                'http_debug_log_channel' => env('FIREBASE_HTTP_DEBUG_LOG_CHANNEL'),
            ],

            'http_client_options' => [

                'proxy' => env('FIREBASE_HTTP_CLIENT_PROXY'),

                'timeout' => env('FIREBASE_HTTP_CLIENT_TIMEOUT'),

                'guzzle_middlewares' => [],
            ],
        ],
    ],
];
