import { differenceInMinutes, differenceInSeconds, addSeconds } from "date-fns";
import { getCountDownStr, getChineseDatetimeStr, getChineseWeek } from "../utils";

const submitBtnPlaceholderEl = document.querySelector("#submitBtnPlaceholder");
const submitBtnEl = document.querySelector("#submitBtn");
const registraionListEl = document.querySelector("#registraionList");

const registerStartAtEl = document.querySelector("#registerStartAt");
const registerStartAtStr = registerStartAtEl.dataset.datetime;
const registerStartAt = addSeconds(registerStartAtStr, 1);
registerStartAtEl.innerText = getChineseDatetimeStr(registerStartAt);

const registerEndAtEl = document.querySelector("#registerEndAt");
const registerEndAtStr = registerEndAtEl.dataset.datetime;
const registerEndAt = new Date(registerEndAtStr);
registerEndAtEl.innerText = getChineseDatetimeStr(registerEndAt);

const startAtTimeEl = document.querySelector("#startAtTime");
const startAtTimeStr = startAtTimeEl.dataset.datetime;
const startAtTime = new Date(startAtTimeStr);
startAtTimeEl.innerText = 
    `${getChineseWeek(startAtTime.getDay())} ${startAtTime.getHours().toString().padStart(2, '0')}:${startAtTime.getMinutes().toString().padStart(2, '0')}`

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
            // remaining time is less than ten minutes
            submitBtnPlaceholderEl.innerText = `將於 ${getCountDownStr(
                registerStartAt
            )}後開放報名`;
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
    submitBtnEl.style.display = "grid";
}

function showSubmitButtonPlacehoder(text) {
    submitBtnPlaceholderEl.innerText = text;
    submitBtnPlaceholderEl.style.display = "grid";
    submitBtnEl.style.display = "none";
}
