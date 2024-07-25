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
        return `${days} 天 ${hours} 小時 ${minutes + 1} 分鐘`;
    }

    if (hours > 0) {
        return `${hours} 小時 ${minutes + 1} 分鐘`;
    }

    if (minutes > 0) {
        return `${minutes + 1} 分鐘`;
    }

    return `${seconds} 秒`;
};
