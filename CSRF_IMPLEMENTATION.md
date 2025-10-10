# CSRF Protection Implementation Guide

## Overview
This document describes the comprehensive CSRF (Cross-Site Request Forgery) protection implementation for the clothing management system.

## Implementation Details

### 1. CSRF Helper Class (`app/helpers/CsrfHelper.php`)

The `CsrfHelper` class provides centralized CSRF token management with the following features:

- **Cryptographically secure token generation** using `random_bytes()`
- **Token expiration** (1 hour default)
- **Multiple validation methods** for different request types
- **Automatic token regeneration** on login
- **PHP 5.3 compatibility**

#### Key Methods:
- `generateToken()` - Creates new CSRF token
- `getToken()` - Retrieves current token
- `validateToken()` - Validates POST form tokens
- `validateTokenFromJson()` - Validates JSON request tokens
- `getTokenField()` - Generates HTML input field
- `getErrorResponse()` - Returns standardized error response

### 2. Session Management Integration

The `SessionManager` class has been updated to:
- Initialize CSRF tokens on session start
- Regenerate tokens on login for security
- Maintain backward compatibility with existing CSRF implementation

### 3. Frontend Integration

#### JavaScript Utilities (`script/utils.js`)
Added CSRF helper functions:
- `getCsrfToken()` - Retrieves token from meta tag
- `addCsrfToFormData()` - Adds token to FormData objects
- `addCsrfToObject()` - Adds token to JSON objects
- `getCsrfHeaders()` - Returns headers for AJAX requests

#### Form Submissions (`App.js`)
All form submissions now automatically include CSRF tokens via the `addCsrfToFormData()` function.

#### AJAX Requests
Updated modules like `ChangeStatus.js` to include CSRF tokens in JSON requests using `addCsrfToObject()`.

### 4. Server-Side Validation

#### Form Handlers
All form handlers (`app/forms/*.php`) now include:
```php
if (!CsrfHelper::validateToken()) {
    $response['success'] = false;
    $response['message'] = "Błąd bezpieczeństwa. Odśwież stronę i spróbuj ponownie.";
    header("Content-Type: application/json");
    echo json_encode($response);
    exit;
}
```

#### AJAX Handlers
AJAX handlers (`handlers/*.php`) validate JSON requests:
```php
if (!CsrfHelper::validateTokenFromJson($data)) {
    echo json_encode(CsrfHelper::getErrorResponse());
    exit;
}
```

### 5. View Integration

All forms now include CSRF tokens via:
```php
<?php echo CsrfHelper::getTokenField(); ?>
```

The header template includes CSRF token in meta tag for JavaScript access:
```php
<meta name="csrf-token" content="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
```

## Security Features

### 1. Token Security
- **64-character tokens** (32 bytes hex-encoded)
- **Cryptographically secure** random generation
- **Time-based expiration** prevents token reuse
- **Session-based storage** ensures proper isolation

### 2. Validation Methods
- **Timing-safe comparison** using `hash_equals()`
- **Multiple validation paths** for different request types
- **Automatic token regeneration** on security events

### 3. Error Handling
- **Standardized error responses** across all endpoints
- **Proper HTTP status codes** (403 for CSRF failures)
- **User-friendly error messages** in Polish

## Usage Examples

### Form Submission
```php
// In view file
<form method="post" action="/handler">
    <?php echo CsrfHelper::getTokenField(); ?>
    <!-- form fields -->
</form>

// In handler file
if (!CsrfHelper::validateToken()) {
    // Handle CSRF error
}
```

### AJAX Request
```javascript
// JavaScript
const requestData = addCsrfToObject({ id: 123, action: 'update' });
fetch('/handler', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(requestData)
});

// PHP handler
$data = json_decode(file_get_contents("php://input"), true);
if (!CsrfHelper::validateTokenFromJson($data)) {
    echo json_encode(CsrfHelper::getErrorResponse());
    exit;
}
```

## Configuration

### Token Expiration
Default token expiration is 1 hour (3600 seconds). To modify:
```php
// In CsrfHelper.php
const TOKEN_MAX_AGE = 7200; // 2 hours
```

### Session Security
For production environments, add these to your PHP configuration:
```php
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1); // For HTTPS
ini_set('session.use_strict_mode', 1);
```

## Testing CSRF Protection

### 1. Test Form Submission
- Submit form without CSRF token → Should fail
- Submit form with invalid token → Should fail
- Submit form with valid token → Should succeed

### 2. Test AJAX Requests
- Send JSON request without CSRF token → Should fail
- Send JSON request with invalid token → Should fail
- Send JSON request with valid token → Should succeed

### 3. Test Token Expiration
- Wait for token to expire (1 hour)
- Submit form with expired token → Should fail
- Refresh page to get new token → Should succeed

## Migration Notes

### Backward Compatibility
The implementation maintains compatibility with the existing CSRF system:
- Old `$_SESSION['csrf']` tokens are still supported
- Existing forms continue to work
- Gradual migration is possible

### Performance Impact
- **Minimal overhead** - tokens are generated once per session
- **Fast validation** - simple string comparison
- **Memory efficient** - tokens stored in session

## Troubleshooting

### Common Issues

1. **"CSRF token validation failed"**
   - Check if session is started
   - Verify token is included in request
   - Ensure token hasn't expired

2. **JavaScript errors**
   - Verify CSRF token meta tag is present
   - Check browser console for errors
   - Ensure utils.js is loaded

3. **Form submission failures**
   - Check if `CsrfHelper::getTokenField()` is called
   - Verify form method is POST
   - Ensure handler includes CSRF validation

### Debug Mode
To enable debug logging, add to your error log:
```php
error_log("CSRF Debug: Token validation failed for " . $_SERVER['REQUEST_URI']);
```

## Security Considerations

### 1. HTTPS Recommendation
While CSRF protection works over HTTP, HTTPS is recommended for:
- Preventing token interception
- Securing session cookies
- Protecting sensitive data

### 2. Additional Security Measures
Consider implementing:
- **Rate limiting** on sensitive endpoints
- **Input validation** beyond CSRF
- **SQL injection prevention** (already implemented)
- **XSS protection** (already implemented with `htmlspecialchars()`)

### 3. Token Rotation
Tokens are automatically regenerated on:
- User login
- Session start (if no token exists)
- Manual regeneration via `regenerateToken()`

This implementation provides comprehensive CSRF protection while maintaining PHP 5.3 compatibility and ensuring optimal performance for your clothing management system.
