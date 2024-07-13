import flatpickr from "flatpickr";
import { MandarinTraditional } from "flatpickr/dist/l10n/zh-tw";
import "flatpickr/dist/flatpickr.min.css";
import { format, subDays } from "date-fns";
import axios from "axios";

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

window.deleteGroupEvent = function (url, redirct) {
    window.popup.confirm("是否確定刪除季打？", (result) => {
        if (result.isDenied) return;
        window.axios.delete(url);
        axios.delete(url).then(({ status }) => {
            if (status === 200) {
                window.popup.success("刪除成功", () => {
                    window.location.href = redirct;
                });
            }
        });
    });
};
