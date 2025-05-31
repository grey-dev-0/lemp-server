import * as monaco from 'monaco-editor';

export function registerNginxLanguage() {
    monaco.languages.register({ id: 'nginx' });

    monaco.languages.setMonarchTokensProvider('nginx', {
        keywords: [
            'daemon', 'env', 'debug_points', 'error_log', 'include', 'events',
            'http', 'server', 'location', 'types', 'if', 'return', 'break',
            'rewrite', 'set', 'proxy_pass', 'fastcgi_pass', 'try_files'
        ],

        directives: [
            'worker_processes', 'worker_connections', 'keepalive_timeout',
            'listen', 'server_name', 'root', 'index', 'access_log',
            'proxy_set_header', 'proxy_cache', 'proxy_cache_use_stale',
            'fastcgi_index', 'fastcgi_param',
            'ssl_certificate', 'ssl_certificate_key', 'ssl_protocols',
            'ssl_ciphers', 'ssl_prefer_server_ciphers', 'ssl_session_cache',
            'ssl_session_timeout', 'ssl_session_tickets', 'ssl_stapling',
            'ssl_stapling_verify', 'ssl_trusted_certificate',
            'ssl_early_data', 'ssl_dhparam', 'ssl_ecdh_curve',
            'ssl_buffer_size', 'ssl_client_certificate', 'ssl_verify_client',
            'ssl_verify_depth', 'ssl_password_file', 'ssl_conf_command',
            'ssl_reject_handshake', 'ssl_ocsp', 'ssl_ocsp_cache',
            'ssl_ocsp_responder', 'ssl_verify_server_cert',
            'proxy_http_version', 'proxy_read_timeout', 'proxy_send_timeout',
            'proxy_connect_timeout', 'proxy_redirect'
        ],

        tokenizer: {
            root: [
                // Comments
                [/#.*$/, 'comment'],

                // String literals
                [/"([^"\\]|\\.)*$/, 'string.invalid'],
                [/'([^'\\]|\\.)*$/, 'string.invalid'],
                [/"/, 'string', '@string_double'],
                [/'/, 'string', '@string_single'],

                // Variables
                [/\$[a-zA-Z_]\w*/, 'variable'],

                // Keywords
                [/\b(?:daemon|env|debug_points|error_log|include|events|http|server|location|types|if|return|break|rewrite|set|proxy_pass|fastcgi_pass|try_files|ssl|http2)\b/, 'keyword'],

                // Directives
                [/\b(?:worker_processes|worker_connections|keepalive_timeout|listen|server_name|root|index|access_log|proxy_set_header|proxy_cache|proxy_cache_use_stale|fastcgi_index|fastcgi_param|ssl_certificate|ssl_certificate_key|ssl_protocols|ssl_ciphers|ssl_prefer_server_ciphers|ssl_session_cache|ssl_session_timeout|ssl_session_tickets|ssl_stapling|ssl_stapling_verify|ssl_trusted_certificate|ssl_early_data|ssl_dhparam|ssl_ecdh_curve|ssl_buffer_size|ssl_client_certificate|ssl_verify_client|ssl_verify_depth|ssl_password_file|ssl_conf_command|ssl_reject_handshake|ssl_ocsp|ssl_ocsp_cache|ssl_ocsp_responder|ssl_verify_server_cert|proxy_http_version|proxy_read_timeout|proxy_send_timeout|proxy_connect_timeout|proxy_redirect)\b/, 'type.identifier'],

                // Numbers
                [/\b\d+[kmgKMG]?\b/, 'number'],

                // Identifiers
                [/[a-zA-Z_]\w*/, 'identifier'],
            ],

            string_double: [
                [/[^\\"]+/, 'string'],
                [/\\./, 'string.escape'],
                [/"/, 'string', '@pop']
            ],

            string_single: [
                [/[^\\']+/, 'string'],
                [/\\./, 'string.escape'],
                [/'/, 'string', '@pop']
            ],
        }
    });
}