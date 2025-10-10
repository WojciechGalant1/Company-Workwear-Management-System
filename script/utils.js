export const debounce = (func, wait) => {
    let timeout;
    return (...args) => {
        clearTimeout(timeout);
        timeout = setTimeout(() => func(...args), wait);
    };
};

export const getBaseUrl = () => {
    const metaBaseUrl = document.querySelector('meta[name="base-url"]');
    return metaBaseUrl ? metaBaseUrl.getAttribute('content') : '';
};

export const getCsrfToken = () => {
    const metaCsrfToken = document.querySelector('meta[name="csrf-token"]');
    return metaCsrfToken ? metaCsrfToken.getAttribute('content') : '';
};

export const addCsrfToFormData = (formData) => {
    const csrfToken = getCsrfToken();
    if (csrfToken) {
        formData.append('csrf_token', csrfToken);
    }
    return formData;
};

export const addCsrfToObject = (data) => {
    const csrfToken = getCsrfToken();
    if (csrfToken) {
        data.csrf_token = csrfToken;
    }
    return data;
};

export const getCsrfHeaders = () => {
    const csrfToken = getCsrfToken();
    return csrfToken ? {
        'X-CSRF-Token': csrfToken,
        'Content-Type': 'application/json'
    } : {
        'Content-Type': 'application/json'
    };
};