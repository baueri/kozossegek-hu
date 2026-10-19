/**
 * GDPR-oriented cookie consent: optional categories off by default, scripts load only after consent.
 */
(function () {
    'use strict';

    var CONSENT_COOKIE = 'kzh_cookie_consent';
    var LEGACY_COOKIE = 'acceptCookies';
    var CONSENT_VERSION = 1;

    var banner = document.getElementById('cookie-consent-banner');
    var configEl = document.getElementById('cookie-consent-config');
    if (!banner) {
        return;
    }

    var trackingInjected = { analytics: false, marketing: false };

    function getConfig() {
        if (!configEl) {
            return { gaId: '', fbAppId: '', fbLocale: 'hu_HU' };
        }
        return {
            gaId: configEl.getAttribute('data-ga-id') || '',
            fbAppId: configEl.getAttribute('data-fb-app-id') || '',
            fbLocale: configEl.getAttribute('data-fb-locale') || 'hu_HU'
        };
    }

    function getCookie(name) {
        var prefix = name + '=';
        var parts = document.cookie.split(';');
        for (var i = 0; i < parts.length; i++) {
            var c = parts[i].trim();
            if (c.indexOf(prefix) === 0) {
                return decodeURIComponent(c.substring(prefix.length));
            }
        }
        return '';
    }

    function setConsentCookie(value) {
        var maxAge = 365 * 24 * 60 * 60;
        var secure = window.location.protocol === 'https:' ? ';Secure' : '';
        document.cookie = CONSENT_COOKIE + '=' + encodeURIComponent(value)
            + ';path=/;max-age=' + maxAge + ';SameSite=Lax' + secure;
    }

    function eraseCookie(name) {
        document.cookie = name + '=;path=/;max-age=0';
    }

    function parseConsent(raw) {
        if (!raw) {
            return null;
        }
        try {
            var o = JSON.parse(raw);
            if (o && typeof o === 'object' && o.v === CONSENT_VERSION) {
                return {
                    v: o.v,
                    analytics: !!o.analytics,
                    marketing: !!o.marketing
                };
            }
        } catch (e) { /* ignore */ }
        return null;
    }

    function readConsent() {
        var parsed = parseConsent(getCookie(CONSENT_COOKIE));
        if (parsed) {
            return parsed;
        }
        if (getCookie(LEGACY_COOKIE) === 'true') {
            var migrated = { v: CONSENT_VERSION, analytics: true, marketing: true };
            setConsentCookie(JSON.stringify(migrated));
            eraseCookie(LEGACY_COOKIE);
            return migrated;
        }
        return null;
    }

    function saveConsent(analytics, marketing) {
        var payload = JSON.stringify({
            v: CONSENT_VERSION,
            analytics: !!analytics,
            marketing: !!marketing
        });
        setConsentCookie(payload);
    }

    function loadAnalytics(gaId) {
        if (!gaId || trackingInjected.analytics) {
            return;
        }
        trackingInjected.analytics = true;
        var s = document.createElement('script');
        s.async = true;
        s.src = 'https://www.googletagmanager.com/gtag/js?id=' + encodeURIComponent(gaId);
        document.head.appendChild(s);
        s.onload = function () {
            window.dataLayer = window.dataLayer || [];
            function gtag() { dataLayer.push(arguments); }
            window.gtag = gtag;
            gtag('js', new Date());
            gtag('config', gaId, { anonymize_ip: true });
        };
    }

    function loadFacebookSdk(appId, locale) {
        if (!appId || trackingInjected.marketing) {
            return;
        }
        trackingInjected.marketing = true;
        var s = document.createElement('script');
        s.async = true;
        s.defer = true;
        s.crossOrigin = 'anonymous';
        s.src = 'https://connect.facebook.net/' + locale + '/sdk.js#xfbml=1&version=v17.0&appId='
            + encodeURIComponent(appId) + '&autoLogAppEvents=1';
        document.body.appendChild(s);
    }

    function applyConsent(consent) {
        var cfg = getConfig();
        if (consent && consent.analytics && cfg.gaId) {
            loadAnalytics(cfg.gaId);
        }
        if (consent && consent.marketing && cfg.fbAppId) {
            loadFacebookSdk(cfg.fbAppId, cfg.fbLocale);
        }
        window.dispatchEvent(new CustomEvent('cookieConsentUpdated', { detail: consent }));
    }

    var detailsEl = banner.querySelector('.cookie-consent-details');
    var cbAnalytics = banner.querySelector('.cookie-consent-checkbox-analytics');
    var cbMarketing = banner.querySelector('.cookie-consent-checkbox-marketing');
    var rowAnalytics = banner.querySelector('.cookie-consent-row-analytics');
    var rowMarketing = banner.querySelector('.cookie-consent-row-marketing');

    function syncOptionalRowsVisibility() {
        var cfg = getConfig();
        if (rowAnalytics) {
            rowAnalytics.hidden = !cfg.gaId;
        }
        if (rowMarketing) {
            rowMarketing.hidden = !cfg.fbAppId;
        }
    }

    function showBanner(openDetails) {
        syncOptionalRowsVisibility();
        banner.hidden = false;
        banner.removeAttribute('hidden');
        banner.setAttribute('aria-hidden', 'false');
        banner.classList.add('show');
        if (detailsEl) {
            detailsEl.hidden = !openDetails;
            if (openDetails) {
                detailsEl.removeAttribute('hidden');
            } else {
                detailsEl.setAttribute('hidden', '');
            }
        }
    }

    function hideBanner() {
        banner.classList.remove('show');
        banner.setAttribute('aria-hidden', 'true');
        banner.hidden = true;
        banner.setAttribute('hidden', '');
        if (detailsEl) {
            detailsEl.hidden = true;
            detailsEl.setAttribute('hidden', '');
        }
    }

    /** Reload so already-loaded trackers (e.g. GA) stop after the user withdraws consent. */
    function shouldReloadAfterWithdrawal(prev, next) {
        if (!prev) {
            return false;
        }
        return (prev.analytics && !next.analytics) || (prev.marketing && !next.marketing);
    }

    function finishSave(prevConsent, nextConsent) {
        saveConsent(nextConsent.analytics, nextConsent.marketing);
        var needReload = shouldReloadAfterWithdrawal(prevConsent, nextConsent);
        applyConsent(nextConsent);
        hideBanner();
        if (needReload) {
            window.location.reload();
        }
    }

    var existing = readConsent();
    if (existing) {
        applyConsent(existing);
    } else {
        showBanner(false);
    }

    window.openCookiePreferences = function () {
        var c = readConsent();
        if (cbAnalytics) {
            cbAnalytics.checked = !!(c && c.analytics);
        }
        if (cbMarketing) {
            cbMarketing.checked = !!(c && c.marketing);
        }
        showBanner(true);
    };

    banner.querySelector('.cookie-consent-reject').addEventListener('click', function () {
        var prev = readConsent();
        var next = { v: CONSENT_VERSION, analytics: false, marketing: false };
        finishSave(prev, next);
    });

    banner.querySelector('.cookie-consent-accept-all').addEventListener('click', function () {
        var prev = readConsent();
        var cfg = getConfig();
        var next = {
            v: CONSENT_VERSION,
            analytics: !!cfg.gaId,
            marketing: !!cfg.fbAppId
        };
        finishSave(prev, next);
    });

    banner.querySelector('.cookie-consent-toggle-details').addEventListener('click', function () {
        if (!detailsEl) {
            return;
        }
        var open = detailsEl.hidden;
        detailsEl.hidden = !open;
        if (open) {
            detailsEl.removeAttribute('hidden');
        } else {
            detailsEl.setAttribute('hidden', '');
        }
    });

    banner.querySelector('.cookie-consent-save').addEventListener('click', function () {
        var prev = readConsent();
        var cfg = getConfig();
        var next = {
            v: CONSENT_VERSION,
            analytics: !!(cfg.gaId && cbAnalytics && cbAnalytics.checked),
            marketing: !!(cfg.fbAppId && cbMarketing && cbMarketing.checked)
        };
        finishSave(prev, next);
    });

    document.querySelectorAll('.js-open-cookie-settings').forEach(function (el) {
        el.addEventListener('click', function (e) {
            e.preventDefault();
            window.openCookiePreferences();
        });
    });
})();
