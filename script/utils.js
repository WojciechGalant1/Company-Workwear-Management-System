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