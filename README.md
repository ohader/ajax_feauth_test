# TYPO3 Frontend User Authentication via AJAX Test Extension

## Meta

* extension key `ajax_feauth_test`
* package name `oliver-hader/ajax-feauth-test`

## Usage/Testing

* activate this extension and make sure TYPO3 caches are cleared
* create a new content element for the plugin named `AJAX FeAuth Test`

This plugin demonstrates a login form which communicates to the
server via AJAX. The ES6 module `login.js` basically has two aspects:

* URI `[site-base]/@api/ajax-feauth-test/preflight`
  + fetches a new `RequestToken`, to be used in the `auth` process
  + fetches (and implicitly sets) new `typo3nonce` cookies
  + responses look like this
    ```json
    {
      "requestToken": {
        "value": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiIsImtpZCI6IntcInR5cGVcIjpcIm5vbmNlXCIsXCJuYW1lXCI6XCJHX0lodHNjYmRTMzBfLW9nNWlIM1VRXCJ9In0.eyJzY29wZSI6ImNvcmUvdXNlci1hdXRoL2ZlIiwidGltZSI6IjIwMjMtMDUtMTBUMDk6NDc6NTMrMDA6MDAiLCJwYXJhbXMiOnsicGlkIjoiMyJ9fQ.tv_-otdGFvQEv_Rgom02fp8T8F8IsPl__m5HndMtmiM",
        "paramName": "__RequestToken",
        "headerName": "X-TYPO3-RequestToken"
      },
      "user": {
        "loggedIn": false,
        "groups": [],
        "username": null
      }
    }
    ```

* URI `[site-base]/@api/ajax-feauth-test/auth`
  + endpoint called with HTTP header `X-TYPO3-RequestToken` using the previously fetched `RequestToken` value
  + the actual authentication is still performed by TYPO3
  + responses look like this (depending on whether valid credentials have been used)
    ```json
    {
      "user": {
        "loggedIn": true,
        "groups": [
          "Usergroup"
        ],
        "username": "admin"
      }
    }
    ```
