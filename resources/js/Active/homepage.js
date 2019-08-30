//監控群組選單
let monitor_group = ['#monitor-water', '#monitor-light', '#monitor-air', '#monitor-weather'];
//監控細項選單
let monitor_items = ['#water-healthy', '#light-healthy', '#air-healthy', '#weather-healthy'];
//關閉按鈕
let monitor_items_closes = ['#water-close', '#light-close', '#air-close', '#weather-close'];

for (let i = 0; i < monitor_group.length; i++) {
    //加入聆聽事件
    $(monitor_group[i]).trigger('click');
    $(monitor_items_closes[i]).trigger('click');

    //點擊發生(monitor_item_open開啟監控細項)
    $(monitor_group[i]).click(() => $(monitor_items[i]).attr('class', 'monitor-item-open text-center'));
    $(monitor_items_closes[i]).click(() => $(monitor_items[i]).attr('class', 'monitor-item-close'));

}

