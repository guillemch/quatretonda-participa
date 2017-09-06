export default class Participa {
    constructor () {
        this.apiURL = '/api/';
    }

    getBallot() {
        return this._call('get', 'ballot');
    }

    getResults(force) {
        console.log(force);
        return this._call('get', 'results', { params: { force } });
    }

    getSidebar() {
        return this._call('get', 'sidebar');
    }

    precheck(data) {
        return this._call('post', 'precheck', data);
    }

    requestSMS(data) {
        return this._call('post', 'request_sms', data);
    }

    castBallot(data) {
        return this._call('post', 'cast_ballot', data);
    }

    anullBallot(data) {
        return this._call('post', 'anull_ballot', data);
    }

    lookUp(data) {
        return this._call('post', 'id_lookup', data);
    }

    _call(type, url, data) {
        return new Promise((resolve, reject) => {
            axios[type](this.apiURL + url, data)
                .then(response => {
                    resolve(response.data);
            })
            .catch(error => {
                reject(error.response.data);
            });
        });
    }
}
