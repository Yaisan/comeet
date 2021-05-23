plugin_paths = { "/usr/share/jitsi-meet/prosody-plugins/" }

-- domain mapper options, must at least have domain base set to use the mapper
muc_mapper_domain_base = "meet.yaisan.cat";

external_service_secret = "KMwvw7JYnHqd1aMd";
external_services = {
     { type = "stun", host = "meet.yaisan.cat", port = 3478 },
     { type = "turn", host = "meet.yaisan.cat", port = 3478, transport = "udp", secret = true, ttl = 86400, algorithm = "turn" },
     { type = "turns", host = "meet.yaisan.cat", port = 5349, transport = "tcp", secret = true, ttl = 86400, algorithm = "turn" }
};

cross_domain_bosh = false;
consider_bosh_secure = true;
-- https_ports = { }; -- Remove this line to prevent listening on port 5284

-- https://ssl-config.mozilla.org/#server=haproxy&version=2.1&config=intermediate&openssl=1.1.0g&guideline=5.4
ssl = {
    protocol = "tlsv1_2+";
    ciphers = "ECDHE-ECDSA-AES128-GCM-SHA256:ECDHE-RSA-AES128-GCM-SHA256:ECDHE-ECDSA-AES256-GCM-SHA384:ECDHE-RSA-AES256-GCM-SHA384:ECDHE-ECDSA-CHACHA20-POLY1305:ECDHE-RSA-CHACHA20-POLY1305:DHE-RSA-AES128-GCM-SHA256:DHE-RSA-AES256-GCM-SHA384"
}

VirtualHost "meet.yaisan.cat"
    -- enabled = false -- Remove this line to enable this host
    authentication = "internal_hashed"
    -- Properties below are modified by jitsi-meet-tokens package config
    -- and authentication above is switched to "token"
    --app_id="example_app_id"
    --app_secret="example_app_secret"
    -- Assign this host a certificate for TLS, otherwise it would use the one
    -- set in the global section (if any).
    -- Note that old-style SSL on port 5223 only supports one certificate, and will always
    -- use the global one.
    ssl = {
        key = "/etc/prosody/certs/meet.yaisan.cat.key";
        certificate = "/etc/prosody/certs/meet.yaisan.cat.crt";
    }
    speakerstats_component = "speakerstats.meet.yaisan.cat"
    conference_duration_component = "conferenceduration.meet.yaisan.cat"
    -- we need bosh
    modules_enabled = {
        "bosh";
        "pubsub";
        "ping"; -- Enable mod_ping
        "speakerstats";
        "external_services";
        "conference_duration";
        "muc_lobby_rooms";
    }
    c2s_require_encryption = false
    lobby_muc = "lobby.meet.yaisan.cat"
    main_muc = "conference.meet.yaisan.cat"
    -- muc_lobby_whitelist = { "recorder.meet.yaisan.cat" } -- Here we can whitelist jibri to enter lobby enabled rooms

VirtualHost "guest.meet.yaisan.cat"
    authentication = "anonymous"
    c2s_require_encryption = false

Component "conference.meet.yaisan.cat" "muc"
    muc_room_default_persistent = true
    storage = "internal"
    modules_enabled = {
        "muc_meeting_id";
        "muc_domain_mapper";
        --"token_verification";
        "whitelist_jicofo";
    }
    admins = { "focus@auth.meet.yaisan.cat" }
    muc_room_locking = false
    muc_room_default_public_jids = true

-- internal muc component
Component "internal.auth.meet.yaisan.cat" "muc"
    storage = "memory"
    modules_enabled = {
        "ping";
    }
    admins = { "focus@auth.meet.yaisan.cat", "jvb@auth.meet.yaisan.cat" }
    muc_room_locking = false
    muc_room_default_public_jids = true

VirtualHost "auth.meet.yaisan.cat"
    ssl = {
        key = "/etc/prosody/certs/auth.meet.yaisan.cat.key";
        certificate = "/etc/prosody/certs/auth.meet.yaisan.cat.crt";
    }
    authentication = "internal_hashed"

-- Proxy to jicofo's user JID, so that it doesn't have to register as a component.
Component "focus.meet.yaisan.cat" "client_proxy"
    target_address = "focus@auth.meet.yaisan.cat"

Component "speakerstats.meet.yaisan.cat" "speakerstats_component"
    muc_component = "conference.meet.yaisan.cat"

Component "conferenceduration.meet.yaisan.cat" "conference_duration_component"
    muc_component = "conference.meet.yaisan.cat"

Component "lobby.meet.yaisan.cat" "muc"
    storage = "memory"
    restrict_room_creation = true
    muc_room_locking = false
    muc_room_default_public_jids = true
