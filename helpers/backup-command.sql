-- -----------------------
-- (1) users table
-- -----------------------
    -- Step 1: Create the new 'users' table with specific columns
        CREATE TABLE africa_relief.users (
            id BIGINT(20) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
            login_name VARCHAR(60),
            password VARCHAR(255) NOT NULL,
            email VARCHAR(100) NOT NULL,
            name VARCHAR(255) NOT NULL,
            phone VARCHAR(255),
            address VARCHAR(255),
            img VARCHAR(255),
            remember_token VARCHAR(100),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX(email, login_name)
        );
    -- Step 2: Insert data into the new 'users' table from the 'afj77_users' table from old db
        INSERT INTO
            africa_relief.users (id, login_name, password, email, name)
        SELECT
            ID, user_login, user_pass, user_email, display_name
        FROM
            africa_relief_wp.afj77_users;






-- -----------------------
-- (2) donors table
-- -----------------------
    -- Step 1: Create the new 'donors' table with specific columns
        CREATE TABLE africa_relief.donors (
            id BIGINT(20) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
            user_id BIGINT(20) DEFAULT 0,
            email VARCHAR(100) NOT NULL,
            stripe_customer_id VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX(email, user_id, stripe_customer_id)
        );
    -- Step 2: Insert data into the new 'donors' table from the 'afj77_give_donors' table from old db
        INSERT INTO
            africa_relief.donors (id, user_id, email, stripe_customer_id)
        SELECT
            donors.id,
            donors.user_id,
            donors.email,
            donormeta.meta_value AS stripe_customer_id
        FROM
            africa_relief_wp.afj77_give_donors AS donors
        JOIN
            africa_relief_wp.afj77_give_donormeta AS donormeta
        ON
            donors.id = donormeta.donor_id
        WHERE
            donormeta.meta_key = '_give_stripe_customer_id';









-- -----------------------
-- (3) subscriptions table
-- -----------------------
    -- Step 1: Create the new 'subscriptions' table with specific columns
        CREATE TABLE subscriptions (
            id BIGINT(20) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
            donor_id BIGINT(20) NOT NULL,
            donation_id BIGINT(20) NOT NULL, -- parent donation id
            stripe_subscription_id VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
            period VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
            initial_amount DECIMAL(10,2) NOT NULL,
            recurring_amount DECIMAL(10,2) NOT NULL,
            status VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
            notes LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
            created DATETIME NOT NULL,
            expiration DATETIME NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX(donor_id, donation_id)
        );
    -- Step 2: Insert data into the new 'subscriptions' table from the 'afj77_give_subscriptions' table from old db
        INSERT INTO
            africa_relief.subscriptions (id, donor_id, donation_id, stripe_subscription_id, period, initial_amount, recurring_amount, status, notes, created, expiration)
        SELECT
            id,
            customer_id As donor_id,
            parent_payment_id As donation_id,
            profile_id As stripe_subscription_id,
            period,
            initial_amount,
            recurring_amount,
            status,
            notes,
            created,
            expiration
        FROM
            africa_relief_wp.afj77_give_subscriptions;






-- -----------------------
-- (4) donations table
-- -----------------------
    -- Step 1: Create the new 'donations' table with specific columns
        CREATE TABLE africa_relief.donations (
            id BIGINT(20) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
            donor_id BIGINT(20),
            subscription_id BIGINT(20) DEFAULT 0,
            form_id BIGINT(20),
            project_title VARCHAR(255),
            amount DECIMAL(10,2),
            currency VARCHAR(255),
            ip_address VARCHAR(100),
            payment_mode VARCHAR(255),
            payment_gateway VARCHAR(255),
            payment_transaction_id VARCHAR(255),
            first_name VARCHAR(255),
            last_name VARCHAR(255),
            phone VARCHAR(255),
            country VARCHAR(255),
            city VARCHAR(255),
            state VARCHAR(255),
            zip VARCHAR(255),
            address1 VARCHAR(255),
            address2 VARCHAR(255),
            comment VARCHAR(255),
            completed_date VARCHAR(255),   -- Not (TIMESTAMP) because it sometimes retrieved null records
            anonymous_donation VARCHAR(255),
            cs_exchange_rate VARCHAR(10),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX(donor_id, subscription_id)
        );
    -- Step 2: Insert data into the new 'donations' table from the 'afj77_give_donationmeta' table from old db
        INSERT INTO africa_relief.donations (
            id,
            donor_id,
            subscription_id,
            form_id,
            project_title,
            amount,
            currency,
            ip_address,
            payment_mode,
            payment_gateway,
            payment_transaction_id,
            phone,
            first_name,
            last_name,
            country,
            city,
            state,
            zip,
            address1,
            address2,
            comment,
            completed_date,
            anonymous_donation,
            cs_exchange_rate
        )
        SELECT
            afj77_give_donationmeta.donation_id AS id,
            MAX(CASE WHEN meta_key = '_give_payment_donor_id' THEN meta_value END) AS donor_id,
            MAX(CASE WHEN meta_key = 'subscription_id' THEN meta_value END) AS subscription_id,
            MAX(CASE WHEN meta_key = '_give_payment_form_id' THEN meta_value END) AS form_id,
            MAX(CASE WHEN meta_key = '_give_payment_form_title' THEN meta_value END) AS project_title,
            MAX(CASE WHEN meta_key = '_give_payment_total' THEN meta_value END) AS amount,
            MAX(CASE WHEN meta_key = '_give_payment_currency' THEN meta_value END) AS currency,
            MAX(CASE WHEN meta_key = '_give_payment_donor_ip' THEN meta_value END) AS ip_address,
            MAX(CASE WHEN meta_key = '_give_payment_mode' THEN meta_value END) AS payment_mode,
            MAX(CASE WHEN meta_key = '_give_payment_gateway' THEN meta_value END) AS payment_gateway,
            MAX(CASE WHEN meta_key = '_give_payment_transaction_id' THEN meta_value END) AS payment_transaction_id,
            MAX(CASE WHEN meta_key = '_give_payment_donor_phone' THEN meta_value END) AS phone,
            MAX(CASE WHEN meta_key = '_give_donor_billing_first_name' THEN meta_value END) AS first_name,
            MAX(CASE WHEN meta_key = '_give_donor_billing_last_name' THEN meta_value END) AS last_name,
            MAX(CASE WHEN meta_key = '_give_donor_billing_country' THEN meta_value END) AS country,
            MAX(CASE WHEN meta_key = '_give_donor_billing_city' THEN meta_value END) AS city,
            MAX(CASE WHEN meta_key = '_give_donor_billing_state' THEN meta_value END) AS state,
            MAX(CASE WHEN meta_key = '_give_donor_billing_zip' THEN meta_value END) AS zip,
            MAX(CASE WHEN meta_key = '_give_donor_billing_address1' THEN meta_value END) AS address1,
            MAX(CASE WHEN meta_key = '_give_donor_billing_address2' THEN meta_value END) AS address2,
            MAX(CASE WHEN meta_key = '_give_donation_comment' THEN meta_value END) AS comment,
            MAX(CASE WHEN meta_key = '_give_completed_date' THEN meta_value END) AS completed_date,
            MAX(CASE WHEN meta_key = '_give_anonymous_donation' THEN meta_value END) AS anonymous_donation,
            MAX(CASE WHEN meta_key = '_give_cs_exchange_rate' THEN meta_value END) AS cs_exchange_rate
        FROM
            africa_relief_wp.afj77_give_donationmeta
        GROUP BY
            afj77_give_donationmeta.donation_id;
