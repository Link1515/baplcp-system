import {
    differenceInDays,
    differenceInHours,
    differenceInMinutes,
    differenceInSeconds,
} from "date-fns";

export const getCountDownStr = (from, to = new Date()) => {
    if (typeof from === "string") {
        from = new Date(from);
    }

    const days = differenceInDays(from, to);
    const hours = differenceInHours(from, to) % 24;
    const minutes = differenceInMinutes(from, to) % 60;
    const seconds = differenceInSeconds(from, to) % 60;

    if (days > 0) {
        return (
            days +
            ":" +
            hours +
            ":" +
            minutes.toString().padStart(2, "0") +
            ":" +
            seconds.toString().padStart(2, "0")
        );
    }

    return (
        hours +
        ":" +
        minutes.toString().padStart(2, "0") +
        ":" +
        seconds.toString().padStart(2, "0")
    );
};
