import { getChineseWeek } from "./index";

export const getChineseDatetimeStr = (date) => {
    return (
        `${(date.getMonth() + 1).toString().padStart(2, "0")} 月 ` +
        `${date.getDate().toString().padStart(2, "0")} 日 ` +
        getChineseWeek(date.getDay()) +
        ` ${date.getHours().toString().padStart(2, "0")}:` +
        date.getMinutes().toString().padStart(2, "0")
    );
};
