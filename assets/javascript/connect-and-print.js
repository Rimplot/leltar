function connectAndPrint(label) {
    // our promise chain
    return connect().then(function() {
        return print(label);
    }).catch(handleConnectionError);
}

function connect() {
    return new RSVP.Promise(function(resolve, reject) {
        if (qz.websocket.isActive()) {	// if already active, resolve immediately
            resolve();
        } else {
            // try to connect once before firing the mimetype launcher
            qz.websocket.connect().then(resolve, function retry() {
                // if a connect was not succesful, launch the mimetime, try 3 more times
                window.location.assign("qz:launch");
                qz.websocket.connect({ retries: 5, delay: 2 }).then(resolve, reject);
            });
        }
    });
}

function handleConnectionError(err) {
    if (err.target != undefined) {
        if (err.target.readyState >= 2) { //if CLOSING or CLOSED
            displayError("Connection to QZ Tray was closed");
        } else {
            displayError("A connection error occurred, check log for details");
            console.error(err);
        }
    } else {
        displayError(err);
    }
}

function print(label) {
    var config = qz.configs.create("Godex G300");
    var data = [label];

    qz.print(config, data).catch(function(e) {
        console.error(e);
    });
}

qz.security.setCertificatePromise(function (resolve, reject) {
    resolve("-----BEGIN CERTIFICATE-----\n" +
            "MIID7zCCAtegAwIBAgIUEZNMHxN9/OXLsrWafEJQdFggyZowDQYJKoZIhvcNAQELBQAwgYUxCzAJBgNVBAYTAlNLMREwDwYDVQQIDAhTbG92YWtpYTERMA8GA1UEBwwIS29twqByb20xHDAaBgNVBAoME0ZpcmVzeiAtIER1bmEgTWVudGUxDzANBgNVBAMMBmxlbHRhcjEhMB8GCSqGSIb3DQEJARYSYWtpc2xiOTlAZ21haWwuY29tMCAXDTE5MDcyNTIzMTIyNVoYDzIwNTEwMTE3MjMxMjI1WjCBhTELMAkGA1UEBhMCU0sxETAPBgNVBAgMCFNsb3Zha2lhMREwDwYDVQQHDAhLb23CoHJvbTEcMBoGA1UECgwTRmlyZXN6IC0gRHVuYSBNZW50ZTEPMA0GA1UEAwwGbGVsdGFyMSEwHwYJKoZIhvcNAQkBFhJha2lzbGI5OUBnbWFpbC5jb20wggEiMA0GCSqGSIb3DQEBAQUAA4IBDwAwggEKAoIBAQDAkLBDuhMj1BHGcY/TEzhPLs52gwgnpCjWhZJqb1QQaYLUr0Kas4rldlsWAKyISLyIPPuQonzkPgmeHGn21wHekZ7cfiNv64LtHFbhykXVV3UppRxEV6/doJtZXVV4VvZ47oFNOwzs86Fz9KFRqUZ/q1nC7wGRMLPXBqdDEMO0iw1Ox+qTj7aQF+pqUAtc9bhSXd4iHLfzifW9ZcSxwZeH8u8Hvbyv94LpkHG7HiVO14SxvJ+wDuHeVh21S4Ak9DgwSJSX2wQatwm4FQEgRkscxogYqJHySZ5hBz2A4HTsCy6pVeP2EdCgbD0OZDu1NRxnV2kq7Ov/XY4GGs1ytgZlAgMBAAGjUzBRMB0GA1UdDgQWBBQuWBqKdYYBBpWyuf2D8JkrOjLvDjAfBgNVHSMEGDAWgBQuWBqKdYYBBpWyuf2D8JkrOjLvDjAPBgNVHRMBAf8EBTADAQH/MA0GCSqGSIb3DQEBCwUAA4IBAQBcubyidmnT1Q/UPRecaKnYS4SmIJxO0KMy4Wewfm/Y9e087mF2/4BUFKDkmmjo6kCfKe1v08yMxggbHksRXYL6/UG+H7kgUiWxRfnVVdUoCUtDCLvmbDa32MOSLfbH85ONf0+m4Vm1BqaAHnvVzyySWA6VooK52h6TiR8kmDRwYGrZHEqMcrLRKYreSCzxaKDbm1KW9SHTnVcrYWb8drkaf16sBS0FqV9iyFpOmYdFpoqlG8wqboIfy6WZE6GEcRTrLGb8Q926RFwPvMOBbmwmk41OPTA0FRE/DLUorwiNJ6ERChORVOIuLnJSADrPqwgXIVqT362X2W90BnZFs6ir\n" +
            "-----END CERTIFICATE-----");
});

var privateKey = "-----BEGIN PRIVATE KEY-----\n" +
        "MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDAkLBDuhMj1BHGcY/TEzhPLs52gwgnpCjWhZJqb1QQaYLUr0Kas4rldlsWAKyISLyIPPuQonzkPgmeHGn21wHekZ7cfiNv64LtHFbhykXVV3UppRxEV6/doJtZXVV4VvZ47oFNOwzs86Fz9KFRqUZ/q1nC7wGRMLPXBqdDEMO0iw1Ox+qTj7aQF+pqUAtc9bhSXd4iHLfzifW9ZcSxwZeH8u8Hvbyv94LpkHG7HiVO14SxvJ+wDuHeVh21S4Ak9DgwSJSX2wQatwm4FQEgRkscxogYqJHySZ5hBz2A4HTsCy6pVeP2EdCgbD0OZDu1NRxnV2kq7Ov/XY4GGs1ytgZlAgMBAAECggEAadwgylq0wvGGhA1R/7uFLSKSJdyK0yAPwz7S6ugg0pTuc3WVq+4f4V1bXZEpSBKUcmDz+uMYvXDNYLpGFojEYlKzfotpd9GEvEzkBmRoV4wowKggDgIWkEQGou4IWouUG48iTg60YF7e1xwYwwfH1c+hHOS/e83GT+M8eEXzEICbpBQ44eryCsrOmQbmxMRDg3gJ4oT20iytzjYH7diyfHRsZWaE2hdSQfeQcX7glaaJfJI6L3lcww2iqPMXe7x5QAHOetwBhd8zgwxkUyDRRDFXYNgO43AfaWy5hr7ygHGo2HGyGBLPT5/jBnKToRXh7aWA81/5JyBtrWkAfL56gQKBgQD0edQwC5lDOelJCQ1ukU1BjKZ/rN/s2IApp088XdQeB671IXgsDAMPVOqUWf9G2Gs9ywyRNBIsOnUnnrASZqQBPKc89VSTo1jB+X1e2irIRdpi87yVNLjtPlueKIQ+5wrkR2jlCW1qZ9k/fbtyCRBkRB3QR+XA6ur3zc6dHhYJaQKBgQDJpG+kqLwg0HLyo/w2OL/3nNkgBjpeMoTH4z3alTzbGFx4Xa6DP8sYMZVHBIaCruA/CNMyB1EQuxUwelQkc1XkYaGjJfX3QpU19u0TbTVfY8PYY20ydliJng0BPEaLWQQ0JCyk0CDO6V3oO1L3Su2k0FIxR/pCwxd1hWqzbi8ZnQKBgQDMHvhSUSzbG3wzVdKYMct+YnAWBAJHH5EKtj4LGhBkLmgfFZ3fsPvRDkZ6LRZeUY5g3qsUhZRyxzBQL0e1ZWuj+L9tvyypxGX0N1o9Gw1DgEdR/U3Eimuo8jrOt+eLGZ9XcGQdb5yijiiuwU0Dbpv4C4OVBqLHS51536WWO6uMmQKBgFAMYGnMtoqMVeWvZfOcidDhymxlLIyyn3W+55I7xqHvxN3fyuS90YF0RZ+g00lT5SAA/96PewYaTuok1fx2cPWuMH3VFxUmsJdwxGL+6r5Im7nulq3+v106ik0gQZ0WJI4SgkqXeT7K9AW6b1BOxZK1RLvdqCMS0eBMq7Q5nKcdAoGAE8IlfxcfQ+y8p4NiN5z1U+PQP6SaWn/Ls1GjUPNwdSzZaMKSSMCdp/5AYGU1wzjptfg+1DjecXfjjOKM0nvcr5o/Gw6CrzsAXmeXjsuGqv5RaW/aGUXMsVujUXa1eThhxivA2JhqPAGHaXBcH2ZxYkZqx9QSa695CCw7o8mnu54=\n" +
        "-----END PRIVATE KEY-----";

qz.security.setSignaturePromise(function(toSign) {
    return function(resolve, reject) {
        try {
            var pk = KEYUTIL.getKey(privateKey);
            var sig = new KJUR.crypto.Signature({"alg": "SHA1withRSA"});
            sig.init(pk);
            sig.updateString(toSign);
            var hex = sig.sign();
            // console.log("DEBUG: \n\n" + stob64(hextorstr(hex)));
            resolve(stob64(hextorstr(hex)));
        } catch (err) {
            console.error(err);
            reject(err);
        }
    };
});