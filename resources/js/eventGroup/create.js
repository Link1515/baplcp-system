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
});

const registerEndDateForEventGroupFlatpickr = flatpickr(
    "#eventGroupRegisterEndAt",
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
    }
);

const registerStartDateForEventGroupFlatpickr = flatpickr(
    "#eventGroupRegisterStartAt",
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
            registerEndDateForEventGroupFlatpickr.config.disable = [
                {
                    from: "1970-01-01",
                    to: dateStr,
                },
            ];
            registerEndDateForEventGroupFlatpickr.clear();
            registerEndDateForEventGroupFlatpickr.redraw();
        },
    }
);
