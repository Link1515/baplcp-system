import {
    differenceInHours,
    differenceInSeconds,
    addSeconds,
    differenceInMinutes,
} from "date-fns";
import { getCountDownStr } from "../utils";

const submitBtnPlaceholderEl = document.querySelector("#submitBtnPlaceholder");
const submitBtnEl = document.querySelector("#submitBtn");
const registraionListEl = document.querySelector("#registraionList");

const registerStartAtStr = document.querySelector("#registerStartAt").innerText;
const registerStartAt = addSeconds(registerStartAtStr, 1);
const registerEndAtStr = document.querySelector("#registerEndAt").innerText;
const registerEndAt = new Date(registerEndAtStr);

let now;
let registerStartRemainingLessThanTenMinutes;
let registerStarted;
let registerEnded;
let timer;

refreshRegistrationStatus();
mainIntervalCallback();
if (!registerEnded) {
    timer = setInterval(mainIntervalCallback, 1000);
}

function mainIntervalCallback() {
    refreshRegistrationStatus();
    checkRegistrationNotStart();
    checkRegistrationStart();
    checkRegistrationEnd();
}

function refreshRegistrationStatus() {
    now = new Date();
    registerStartRemainingLessThanTenMinutes =
        differenceInMinutes(registerStartAt, now) <= 10;
    registerStarted = differenceInSeconds(registerStartAt, now) <= 0;
    registerEnded = differenceInSeconds(registerEndAt, now) <= 0;
}

function checkRegistrationNotStart() {
    if (!registerStarted) {
        if (registerStartRemainingLessThanTenMinutes) {
            // remaining time is less than 10 minutes
            submitBtnPlaceholderEl.innerText = `將於 ${getCountDownStr(
                registerStartAt
            )}後開放報名`;
            if (registerStarted) {
                showSubmitButton();
            }
        } else {
            if (submitBtnPlaceholderEl) {
                showSubmitButtonPlacehoder("報名尚未開放");
            }
        }
    }
}

function checkRegistrationStart() {
    if (registerStarted) {
        if (submitBtnEl) {
            showSubmitButton();
        }
        registraionListEl.style.display = "block";
    }
}

function checkRegistrationEnd() {
    if (registerEnded) {
        if (submitBtnPlaceholderEl) {
            showSubmitButtonPlacehoder("報名已截止");
        }
        clearInterval(timer);
        registraionListEl.style.display = "block";
    }
}

function showSubmitButton() {
    submitBtnPlaceholderEl.style.display = "none";
    submitBtnEl.style.display = "block";
}

function showSubmitButtonPlacehoder(text) {
    submitBtnPlaceholderEl.innerText = text;
    submitBtnPlaceholderEl.style.display = "grid";
    submitBtnEl.style.display = "none";
}
