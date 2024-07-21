import { differenceInHours, differenceInSeconds, addSeconds } from "date-fns";

import { getCountDownStr } from "../utils";

const submitBtnPlaceholderEl = document.querySelector("#submitBtnPlaceholder");
const submitBtnEl = document.querySelector("#submitBtn");

const registerStartAtStr = document.querySelector("#registerStartAt").innerText;
const registerStartAt = addSeconds(registerStartAtStr, 1);
const registerEndAtStr = document.querySelector("#registerEndAt").innerText;
const registerEndAt = new Date(registerEndAtStr);

let now;
let registerStartRemainingLessThanOneHour;
let registerStarted;
let registerEnded;

if (submitBtnEl) {
    mainIntervalCallback();
    const timer = setInterval(mainIntervalCallback, 1000);
}

function mainIntervalCallback() {
    refreshRegistrationStatus();
    checkRegistrationNotStart();
    checkRegistrationStart();
    checkRegistrationEnd();
}

function refreshRegistrationStatus() {
    now = new Date();
    registerStartRemainingLessThanOneHour =
        differenceInHours(registerStartAt, now) <= 0;
    registerStarted = differenceInSeconds(registerStartAt, now) <= 0;
    registerEnded = differenceInSeconds(registerEndAt, now) <= 0;
}

function checkRegistrationNotStart() {
    if (!registerStarted) {
        if (registerStartRemainingLessThanOneHour) {
            // remaining time is less than one hour
            submitBtnPlaceholderEl.innerText = getCountDownStr(registerStartAt);
            if (registerStarted) {
                showSubmitButton();
            }
        } else {
            showSubmitButtonPlacehoder("報名尚未開放");
        }
    }
}

function checkRegistrationStart() {
    if (registerStarted) {
        showSubmitButton();
    }
}

function checkRegistrationEnd() {
    if (registerEnded) {
        showSubmitButtonPlacehoder("報名已截止");
        clearInterval(timer);
    }
}

function showSubmitButton() {
    submitBtnPlaceholderEl.style.display = "none";
    submitBtnEl.style.display = "grid";
}

function showSubmitButtonPlacehoder(text) {
    submitBtnPlaceholderEl.innerText = text;
    submitBtnPlaceholderEl.style.display = "grid";
    submitBtnEl.style.display = "none";
}
