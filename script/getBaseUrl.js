export const getBaseUrl = () => {
    const metaBaseUrl = document.querySelector('meta[name="base-url"]');
    return metaBaseUrl ? metaBaseUrl.getAttribute('content') : '';
};