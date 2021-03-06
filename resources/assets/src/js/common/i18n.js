'use strict';

$.locales       = {};
$.currentLocale = {};

/**
 * Load current selected language.
 *
 * @return void
 */
function loadLocales() {
    for (let lang in $.locales) {
        if (!isEmpty($.locales[lang])) {
            $.currentLocale = $.locales[lang] || {};
        }
    }
}

/**
 * Translate according to given key.
 *
 * @param  {string} key
 * @param  {dict}   parameters
 * @return {string}
 */
function trans(key, parameters = {}) {
    if (isEmpty($.currentLocale)) {
        loadLocales();
    }

    let segments = key.split('.');
    let temp = $.currentLocale || {};

    for (let i in segments) {
        if (isEmpty(temp[segments[i]])) {
            return key;
        } else {
            temp = temp[segments[i]];
        }
    }

    for (let i in parameters) {
        if (!isEmpty(parameters[i])) {
            temp = temp.replace(':'+i, parameters[i]);
        }
    }

    return temp;
}

if (typeof require !== 'undefined' && typeof module !== 'undefined') {
    module.exports = {
        trans,
        loadLocales,
    };
}
