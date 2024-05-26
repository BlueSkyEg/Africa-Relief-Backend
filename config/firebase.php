<?php

declare(strict_types=1);

return [
    /*
     * ------------------------------------------------------------------------
     * Default Firebase project
     * ------------------------------------------------------------------------
     */

    'default' => env('FIREBASE_PROJECT', 'app'),

    /*
     * ------------------------------------------------------------------------
     * Firebase project configurations
     * ------------------------------------------------------------------------
     */

    'projects' => [
        'app' => [

            /*
             * ------------------------------------------------------------------------
             * Credentials / Service Account
             * ------------------------------------------------------------------------
             *
             * In order to access a Firebase project and its related services using a
             * server SDK, requests must be authenticated. For server-to-server
             * communication this is done with a Service Account.
             *
             * If you don't already have generated a Service Account, you can do so by
             * following the instructions from the official documentation pages at
             *
             * https://firebase.google.com/docs/admin/setup#initialize_the_sdk
             *
             * Once you have downloaded the Service Account JSON file, you can use it
             * to configure the package.
             *
             * If you don't provide credentials, the Firebase Admin SDK will try to
             * auto-discover them
             *
             * - by checking the environment variable FIREBASE_CREDENTIALS
             * - by checking the environment variable GOOGLE_APPLICATION_CREDENTIALS
             * - by trying to find Google's well known file
             * - by checking if the application is running on GCE/GCP
             *
             * If no credentials file can be found, an exception will be thrown the
             * first time you try to access a component of the Firebase Admin SDK.
             *
             */

//            'credentials' => env('FIREBASE_CREDENTIALS', env('GOOGLE_APPLICATION_CREDENTIALS')),
              'credentials' => [
                  "type" => "service_account",
                  "project_id" => "africa-relief-413f9",
                  "private_key_id" => "c654de37d7e94d039e601857651e7d9f513414fb",
                  "private_key" => "-----BEGIN PRIVATE KEY-----\nMIIEvwIBADANBgkqhkiG9w0BAQEFAASCBKkwggSlAgEAAoIBAQDcDZWv/qg2VEkW\no8xHB8RGncvFGVifmFq5qpMFi6B5m+ZCXZ1/Xa5tSUq051fLKNyEOWzP3Y36s8TR\nTbRqqpTndGeTg4YexMywNMfEsiAqd1V5YF3oWJYPco6kdcDsC3GCFLitR6KFnZuW\nEcCKN1HodzJ0ikbrN4um0ZJCZbRCqO5uQF1B3tGIodz0m9eMLS6YvZcaiWp1euqJ\nn9dFmM0cywT98f3dm72MDAt9QS2YsGijyOQ0dWtJcSkWy+1c7yak6F2lVPuSfnIm\nmqhClNL0EOFyr+6c+Nto00kwl6lWsTSB9U1oibdTMddc3Trq+P447h3dAzXn3xIt\no0EPoupnAgMBAAECggEAaAxUVt9GkknQNVBF6uAVQn6iNxQA/5V+cIPi+KBIxLee\nbIrvSaX2L96T4G3y6TDs8+hjNvY/h+cwjPb88+KsfbRgtTNfW3Uag8axHqRK6Ul7\ntOSIArDAY5V7NPAoxHO0vBkoKWbRxfP7+T1zmOcHZ6/Uv54SYm3lLn+xdIu6bDmr\nuGzeY64MHhnE2SOIqyMs99bISj7zm+geCOrOtY5QgsJpuKbcRqw/LaX1Icl+TSDP\nqS9RMwIIn//KFKTRAmY5G6nLEurCW6+yfbQRJu51yrZin6Za00FQ02DQFB6rFjMI\nP146QNXvNHs08Jhz1yJ4Jhu5E/beTHep2MG+CedtIQKBgQD39XB6KtC/fNToaDEq\nnUJRNoGeBrg/IqYCoW1RNj7Ij0qsBf10EeFLzBl2LD2GHUkpQB6uXVJbTPAiDH7y\nPSEuyxkk9TfhqMAQzhP5nYg5RtpVpNQylXgA05c8hMdDeeqPg3BqPM2Bp9n9wDi7\nUE7bRXaIuZ/5z196lVxnWvfoDQKBgQDjMHi5WYFEZ9DDniii9vLcXhKZeDbVYgBp\nAWddpJMaQSSkv6RDoOXIveOwpZaBdWV9XYbmBHK0PIq5voma3tgmbcLfflUrCxGz\nrrgxcf9xQLmGkPK/FtXHaTzyOjcsr/MJbmwXF6RquD5tOcsXaqGM/OLSCvfA6MPg\njglDryMrQwKBgQC4XaVxIM6v4oiH+Zi9H8rEieucbVCnmQKoqBCZNuU9yNVzzMxk\ndjr3Wd2AsWpZgwL4CfYGHdtziWRvXK0zPmKi4V5jzXTsc8XDeQb/LbOxKs6CqZkh\nt2NP8gPcermSoV7XsJpwU59WDVEXzMoh9Jd9kEuPY73gR+GtSss3CjuULQKBgQDT\n5RCkJzPm3kEbQnc6T8OB/4evdi0GZkplpbaH44iEE0AfHNMTdIz85wbnafnvMtR+\n0e2QuZNWQaVNysXDGZdWeEcqdkTvSXqwMQSAsYeDVM/1D1opGh43yBLdBakSV+UA\n5emQvC9QjhlDfITMiVq2CeNv7mTXmwGefjtr0nylywKBgQCBDCQYrs9aTcOTfGCK\njX9BDZz2Zr7AjuSHW1gdbusfyz3rkHSqbqTCQmX4xD/t5FhvEzRGGEWCpN64slwx\ne4WrdhM9Mt1HVEiq1IZQwdGCkFs9Z8I2QocnJE4PdsRdnMI4SbwcqL2M68OrMo4B\ne3kvSPbGwUeDh6yK3NEHC/81fw==\n-----END PRIVATE KEY-----\n",
                  "client_email" => "firebase-adminsdk-nomo3@africa-relief-413f9.iam.gserviceaccount.com",
                  "client_id" => "117165429517836734224",
                  "auth_uri" => "https://accounts.google.com/o/oauth2/auth",
                  "token_uri" => "https://oauth2.googleapis.com/token",
                  "auth_provider_x509_cert_url" => "https://www.googleapis.com/oauth2/v1/certs",
                  "client_x509_cert_url" => "https://www.googleapis.com/robot/v1/metadata/x509/firebase-adminsdk-nomo3%40africa-relief-413f9.iam.gserviceaccount.com",
                  "universe_domain" => "googleapis.com"
              ],

            /*
             * ------------------------------------------------------------------------
             * Firebase Auth Component
             * ------------------------------------------------------------------------
             */

            'auth' => [
                'tenant_id' => env('FIREBASE_AUTH_TENANT_ID'),
            ],

            /*
             * ------------------------------------------------------------------------
             * Firestore Component
             * ------------------------------------------------------------------------
             */

            'firestore' => [

                /*
                 * If you want to access a Firestore database other than the default database,
                 * enter its name here.
                 *
                 * By default, the Firestore client will connect to the `(default)` database.
                 *
                 * https://firebase.google.com/docs/firestore/manage-databases
                 */

                // 'database' => env('FIREBASE_FIRESTORE_DATABASE'),
            ],

            /*
             * ------------------------------------------------------------------------
             * Firebase Realtime Database
             * ------------------------------------------------------------------------
             */

            'database' => [

                /*
                 * In most of the cases the project ID defined in the credentials file
                 * determines the URL of your project's Realtime Database. If the
                 * connection to the Realtime Database fails, you can override
                 * its URL with the value you see at
                 *
                 * https://console.firebase.google.com/u/1/project/_/database
                 *
                 * Please make sure that you use a full URL like, for example,
                 * https://my-project-id.firebaseio.com
                 */

                'url' => env('FIREBASE_DATABASE_URL'),

                /*
                 * As a best practice, a service should have access to only the resources it needs.
                 * To get more fine-grained control over the resources a Firebase app instance can access,
                 * use a unique identifier in your Security Rules to represent your service.
                 *
                 * https://firebase.google.com/docs/database/admin/start#authenticate-with-limited-privileges
                 */

                // 'auth_variable_override' => [
                //     'uid' => 'my-service-worker'
                // ],

            ],

            'dynamic_links' => [

                /*
                 * Dynamic links can be built with any URL prefix registered on
                 *
                 * https://console.firebase.google.com/u/1/project/_/durablelinks/links/
                 *
                 * You can define one of those domains as the default for new Dynamic
                 * Links created within your project.
                 *
                 * The value must be a valid domain, for example,
                 * https://example.page.link
                 */

                'default_domain' => env('FIREBASE_DYNAMIC_LINKS_DEFAULT_DOMAIN'),
            ],

            /*
             * ------------------------------------------------------------------------
             * Firebase Cloud Storage
             * ------------------------------------------------------------------------
             */

            'storage' => [

                /*
                 * Your project's default storage bucket usually uses the project ID
                 * as its name. If you have multiple storage buckets and want to
                 * use another one as the default for your application, you can
                 * override it here.
                 */

                'default_bucket' => env('FIREBASE_STORAGE_DEFAULT_BUCKET'),

            ],

            /*
             * ------------------------------------------------------------------------
             * Caching
             * ------------------------------------------------------------------------
             *
             * The Firebase Admin SDK can cache some data returned from the Firebase
             * API, for example Google's public keys used to verify ID tokens.
             *
             */

            'cache_store' => env('FIREBASE_CACHE_STORE', 'file'),

            /*
             * ------------------------------------------------------------------------
             * Logging
             * ------------------------------------------------------------------------
             *
             * Enable logging of HTTP interaction for insights and/or debugging.
             *
             * Log channels are defined in config/logging.php
             *
             * Successful HTTP messages are logged with the log level 'info'.
             * Failed HTTP messages are logged with the log level 'notice'.
             *
             * Note: Using the same channel for simple and debug logs will result in
             * two entries per request and response.
             */

            'logging' => [
                'http_log_channel' => env('FIREBASE_HTTP_LOG_CHANNEL'),
                'http_debug_log_channel' => env('FIREBASE_HTTP_DEBUG_LOG_CHANNEL'),
            ],

            /*
             * ------------------------------------------------------------------------
             * HTTP Client Options
             * ------------------------------------------------------------------------
             *
             * Behavior of the HTTP Client performing the API requests
             */

            'http_client_options' => [

                /*
                 * Use a proxy that all API requests should be passed through.
                 * (default: none)
                 */

                'proxy' => env('FIREBASE_HTTP_CLIENT_PROXY'),

                /*
                 * Set the maximum amount of seconds (float) that can pass before
                 * a request is considered timed out
                 *
                 * The default time out can be reviewed at
                 * https://github.com/kreait/firebase-php/blob/6.x/src/Firebase/Http/HttpClientOptions.php
                 */

                'timeout' => env('FIREBASE_HTTP_CLIENT_TIMEOUT'),

                'guzzle_middlewares' => [],
            ],
        ],
    ],
];
