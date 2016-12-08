class WbsStorage {

    static localStorageSupport() {
        return false; //(('localStorage' in window) && window['localStorage'] !== null)
    }

    static get(key, defaultValue) {

        let value = null;

        if (this.localStorageSupport()) {
            value = localStorage.getItem(key);
        }

        if (value === null) {
            return defaultValue;
        }

        return value;

    }

    static set(key, value) {

        if (this.localStorageSupport()) {
            localStorage.setItem(key, value)
        }

        return value;

    }

}