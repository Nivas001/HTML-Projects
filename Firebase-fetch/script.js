// Import the functions you need from the SDKs you need
import { initializeApp } from "firebase/app";
import { getAnalytics } from "firebase/analytics";
// TODO: Add SDKs for Firebase products that you want to use
// https://firebase.google.com/docs/web/setup#available-libraries

// Your web app's Firebase configuration
// For Firebase JS SDK v7.20.0 and later, measurementId is optional
const firebaseConfig = {
    apiKey: "AIzaSyDIhnuiYqhQArysirjyX4lVIXhsIjlwlL4",
    authDomain: "project-sample-ca132.firebaseapp.com",
    projectId: "project-sample-ca132",
    storageBucket: "project-sample-ca132.appspot.com",
    messagingSenderId: "246167851179",
    appId: "1:246167851179:web:c87a5a342c9f971c6467ee",
    measurementId: "G-LTR4QK45DV"
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);
const analytics = getAnalytics(app);


//my code

const signinForm = document.getElementById('sign-in');
signinForm.addEventListener('submit', (event) => {
    event.preventDefault();
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;

    signInWithEmailAndPassword(auth, email, password)
        .then((userCredential) => {
            // Signed in
            const user = userCredential.user;
            // ...
            console.log("Success");
        })
        .catch((error) => {
            const errorCode = error.code;
            const errorMessage = error.message;
            console.log("Something wrong");
            // ...
        });
});
