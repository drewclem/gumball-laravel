export function useDates() {
    const currentDate = new Date();
    const tomorrow = new Date(currentDate);

    const tomorrowDate = new Date(tomorrow.setDate(tomorrow.getDate() + 1));

    return {
        currentDate,
        tomorrowDate,
    };
}
