import flatpickr from "flatpickr";
import { MandarinTraditional } from "flatpickr/dist/l10n/zh-tw";
import "flatpickr/dist/flatpickr.min.css";
import { format, subDays } from "date-fns";

flatpickr("#eventDates", {
    mode: "multiple",
    dateFormat: "Y-m-d",
    locale: MandarinTraditional,
    disable: [
        {
            from: "1970-01-01",
            to: format(subDays(new Date(), 1), "yyyy-MM-dd"),
        },
    ],
});

flatpickr("#eventTime", {
    enableTime: true,
    noCalendar: true,
    dateFormat: "H:i",
    onChange(selectedDates, dateStr, instance) {
        if (selectedDates[0]) {
            limitMinuteZeroOr30(selectedDates[0], instance);
        }
    },
});

flatpickr("#eventStartRegisterDayBeforeTime", {
    enableTime: true,
    noCalendar: true,
    dateFormat: "H:i",
    onChange(selectedDates, dateStr, instance) {
        if (selectedDates[0]) {
            limitMinuteZeroOr30(selectedDates[0], instance);
        }
    },
});

flatpickr("#eventEndRegisterDayBeforeTime", {
    enableTime: true,
    noCalendar: true,
    dateFormat: "H:i",
    onChange(selectedDates, dateStr, instance) {
        if (selectedDates[0]) {
            limitMinuteZeroOr30(selectedDates[0], instance);
        }
    },
});

const registerEndDateForSeasonFlatpickr = flatpickr("#seasonRegisterEndAt", {
    enableTime: true,
    dateFormat: "Y-m-d H:i",
    locale: MandarinTraditional,
    disable: [
        {
            from: "1970-01-01",
            to: format(subDays(new Date(), 1), "yyyy-MM-dd"),
        },
    ],
    onChange(selectedDates, dateStr, instance) {
        if (selectedDates[0]) {
            limitMinuteZeroOr30(selectedDates[0], instance);
        }
    },
});

const registerStartDateForSeasonFlatpickr = flatpickr(
    "#seasonRegisterStartAt",
    {
        enableTime: true,
        dateFormat: "Y-m-d H:i",
        locale: MandarinTraditional,
        disable: [
            {
                from: "1970-01-01",
                to: format(subDays(new Date(), 1), "yyyy-MM-dd"),
            },
        ],
        onChange(selectedDates, dateStr, instance) {
            if (selectedDates[0]) {
                limitMinuteZeroOr30(selectedDates[0], instance);
            }
            registerEndDateForSeasonFlatpickr.config.disable = [
                {
                    from: "1970-01-01",
                    to: dateStr,
                },
            ];
            registerEndDateForSeasonFlatpickr.clear();
            registerEndDateForSeasonFlatpickr.redraw();
        },
    }
);

function limitMinuteZeroOr30(date, instance) {
    const minutes = date.getMinutes();
    const hours = date.getHours();

    if (minutes === 0 || minutes === 30) return;

    if (minutes > 30) {
        date.setHours(hours + 1);
        date.setMinutes(0);
        instance.setDate(date);
    } else {
        date.setMinutes(30);
        instance.setDate(date);
    }
}
