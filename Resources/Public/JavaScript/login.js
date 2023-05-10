import AjaxRequest from '@typo3/core/ajax/ajax-request.js';

/**
 * Module: @oliver-hader/ajax-feauth-test/login.js
 * @exports @typo3/core/document-service
 */
export default class AjaxFeauthTestLogin {
    constructor(options) {
        this.options = options;
        this.form = document.querySelector('form#ajax-feauth-test');
        this.form.addEventListener('submit', (evt) => this.handleFormSubmit(evt));
    }

    async handleFormSubmit(evt) {
        evt.preventDefault();
        const formData = new FormData(this.form);
        const preflight = await (new AjaxRequest(this.options.preflightUrl))
            .post({})
            .then((response) => response.resolve('application/json'));
        if (!preflight.requestToken) {
            console.warn('Not request token in response');
            return;
        }
        console.log('User Status (after preflight)', preflight.user);
        // user is already logged in
        if (preflight.user.loggedIn) {
            console.warn('Already logged in');
            return;
        }
        const requestToken = preflight.requestToken;
        const headers = new Headers();
        // important: use request-token as HTTP header when authenticating
        headers.set(requestToken.headerName, requestToken.value);
        const auth = await (new AjaxRequest(this.options.authUrl))
            .post(formData, { headers })
            .then((response) => response.resolve('application/json'))
            .then((response) => console.log('User Status (after auth)', response.user));
    }
}
