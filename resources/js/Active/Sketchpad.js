import * as d3 from 'd3';

// use d3 to drew shape & monitor visualize
let monitor_hex_id = ['monitor-water', 'monitor-light', 'monitor-air', 'monitor-weather'];
let monitor_circle_id = [];

let length = 200;


//繪圖資訊
export class Draw_Info {
    constructor(id, value, max, min) {
        this.id = id;
        this.value = value;
        this.max = max;
        this.min = min;
    };
}

export function Make_Hex(info) {
    let d, length,
        height, width,
        cx, cy, radius_outside, radius_inside;
    let data, range;
    let svg, circle_text;
    let color;
    let colorify = [
        '#24A047',//green
        '#E5DD35',//yellow
        '#ED4013',//red
    ];
    let coordinate = [
        [100, 0],
        [25, 50],
        [25, 170],
        [100, 200],
        [175, 50],
        [175, 170],
    ];
    length = 200;
    radius_outside = length * 0.9 / 2;                          //outside半徑
    radius_inside = length * 0.75 / 2;                         //inside半徑
    range = (info.max - info.min) * 100;                         //單位％
    height = length;
    width = length;
    d = length / 2;
    cx = length / 2;
    cy = length / 2;
    data = info.value;


    //抓取圖畫得位置
    svg = d3.select('#' + info.id)
        .append('svg')
        .attr('width', width)
        .attr('height', height)
        .attr('class', 'paint-exponent');
    //畫兩個六角形 一個 圓形
    circle_text = svg.append('g');

    circle_text.append('circle')
        .attr("cx", cx)
        .attr("cy", cy)
        .attr("r", radius_inside)
        .style("fill", '#fff')                           //填色
        .style("stroke", "#fff")                              //邊界顏色
        .style("stroke-width", "2px");                        //邊界粗度

    //加字體
    circle_text.append('text')
        .attr('dx', cx)
        .attr('dy', cy)
        .style("text-anchor", "middle")
        .text(data);
}

export function Make_Circle(info) {
    let height, width,
        color,
        cx, cy, radius_outside, radius_inside;
    let data, range, data_point;
    let svg, circle_text;
    let colorify = [
        '#ED4013',
        '#E5DD35',
        '#24A047',
    ];

    length = 200;
    radius_outside = length * 0.9 / 2;                          //outside半徑
    radius_inside = length * 0.75 / 2;                         //inside半徑
    range = (info.max - info.min);                         //單位％
    height = length;
    width = length;
    cx = length / 2;
    cy = length / 2;
    data = info.value;

    data_point = (data / range) * 100;

    color = Math.ceil(data_point / 30) < 4 ? Math.ceil(data_point / 30) - 1 : Math.ceil(data_point / 30) - 2;
    //抓取圖畫得位置
    svg = d3.select('#' + info.id)
        .append('svg')
        .attr('width', width)
        .attr('height', height)
        .attr('class', 'paint-exponent');
    //畫兩個六角形 一個 圓形
    circle_text = svg.append('g');

    circle_text.append('circle')
        .attr("cx", cx)
        .attr("cy", cy)
        .attr("r", radius_outside)
        .attr("viewBox", "0 0 200 200")
        .style("fill", colorify[color])                           //填色
        .style("stroke", "#fff")                              //邊界顏色
        .style("stroke-width", "2px");                        //邊界粗度

    circle_text.append('circle')
        .attr("cx", cx)
        .attr("cy", cy)
        .attr("r", radius_inside)
        .style("fill", '#fff')                           //填色
        .style("stroke", colorify[color])                              //邊界顏色
        .style("stroke-width", "2px");                        //邊界粗度

    //加字體
    circle_text.append('text')
        .attr('dx', cx)
        .attr('dy', cy + 10)
        .attr('class', 'd3-font-size')
        .style("text-anchor", "middle")
        .text(data);
}


function hex_point() {
    let coordinate = [
        [100, 0],
        [25, 50],
        [25, 170],
        [100, 200],
        [175, 50],
        [175, 170],
    ];

    return coordinate;
}