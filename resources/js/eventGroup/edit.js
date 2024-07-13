import flatpickr from "flatpickr";
import { MandarinTraditional } from "flatpickr/dist/l10n/zh-tw";
import "flatpickr/dist/flatpickr.min.css";
import { format, subDays } from "date-fns";

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
        onChange(selectedDates, dateStr, instance) {
            if (selectedDates[0]) {
                limitMinuteZeroOr30(selectedDates[0], instance);
            }
        },
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
            if (selectedDates[0]) {
                limitMinuteZeroOr30(selectedDates[0], instance);
            }
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

window.deleteGroupEvent = function (url, redirct) {
    window.popup.confirm("是否確定刪除季打？", (result) => {
        if (result.isDenied || result.isDismissed) return;
        window.axios.delete(url).then(({ status }) => {
            if (status === 200) {
                window.popup.success("刪除成功", () => {
                    window.location.href = redirct;
                });
            }
        });
    });
};
