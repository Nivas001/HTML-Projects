// Base PasswordField class
class PasswordField {
    constructor(passwordSelector, eyeSelector) {
        this.passwordField = document.querySelector(passwordSelector);
        this.eyeIcon = document.querySelector(eyeSelector);
        this.initializeToggle();
    }

    initializeToggle() {
        this.eyeIcon.addEventListener('click', () => this.toggleVisibility());
    }

    toggleVisibility() {
        const cursorPosition = this.passwordField.selectionStart;
        this.passwordField.type = this.passwordField.type === "password" ? "text" : "password";
        setTimeout(() => {
            this.passwordField.setSelectionRange(cursorPosition, cursorPosition);
        }, 0);
    }

    validateStrength(submitButton) {
        const password = this.passwordField.value;
        const hasUpper = /[A-Z]/.test(password);
        const hasLower = /[a-z]/.test(password);
        const hasNum = /[0-9]/.test(password);
        const hasSpecial = /[~!@#$%^&*(){};<>?]/.test(password);

        let borderColor = '#836FFF'; // default color

        if (hasUpper || hasLower || hasSpecial || hasNum) {
            borderColor = 'red';
            if (hasNum && hasUpper && hasLower) {
                borderColor = 'yellow';
                if (password.length > 7 && hasSpecial) {
                    borderColor = 'green';
                    submitButton.disabled = false;
                    submitButton.style.cursor = "pointer";
                } else {
                    submitButton.disabled = true;
                    submitButton.style.cursor = "not-allowed";
                }
            } else {
                submitButton.disabled = true;
                submitButton.style.cursor = "not-allowed";
            }
        }
        this.passwordField.style.borderBottom = `3px solid ${borderColor}`;
    }
}

// RegisterPasswordField inherits from PasswordField
class RegisterPasswordField extends PasswordField {
    constructor(passwordSelector, eyeSelector, submitButtonSelector) {
        super(passwordSelector, eyeSelector);
        this.submitButton = document.querySelector(submitButtonSelector);
        this.initializeValidation();
    }

    initializeValidation() {
        this.passwordField.addEventListener('keyup', () => this.validateStrength(this.submitButton));
    }
}

// LoginPasswordField inherits from PasswordField
class LoginPasswordField extends PasswordField {
    constructor(passwordSelector, eyeSelector) {
        super(passwordSelector, eyeSelector);
    }
}

// Class for handling visibility
class VisibilityController {
    constructor(loginSelector, regSelector, titleSelector, loginLinkSelector, regLinkSelector) {
        this.login = document.querySelector(loginSelector);
        this.reg = document.querySelector(regSelector);
        this.rename = document.querySelector(titleSelector);

        this.initializeEvents(loginLinkSelector, regLinkSelector);
    }

    initializeEvents(loginLinkSelector, regLinkSelector) {
        document.querySelector(loginLinkSelector).addEventListener('click', () => this.showLogin());
        document.querySelector(regLinkSelector).addEventListener('click', () => this.showRegister());
    }

    showLogin() {
        this.reg.style.display = "none";
        this.login.style.display = "block";
        this.rename.innerHTML = "Login";
    }

    showRegister() {
        this.login.style.display = "none";
        this.reg.style.display = "block";
        this.rename.innerHTML = "Register";
    }
}

// Main PageManager class
class PageManager {
    constructor() {
        this.visibilityController = new VisibilityController(
            '.login', '.regi', '#title', '.log-link', '.reg-link'
        );

        // Register form password field with validation
        this.registerPasswordField = new RegisterPasswordField(
            '.register-pass', '.register-eye', 'button'
        );

        // Login form password field toggle
        this.loginPasswordField = new LoginPasswordField(
            '.login-pass', '.login-eye'
        );

        this.initializeButton();
    }

    initializeButton() {
        const submitButton = document.querySelector('button');
        submitButton.disabled = true;
        submitButton.classList.remove('bg');
        submitButton.style.cursor = "not-allowed";
    }
}

// Initialize the PageManager
document.addEventListener('DOMContentLoaded', () => {
    new PageManager();
});
