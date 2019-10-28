import * as d3 from 'd3';
import _ from 'lodash';

// use d3 to drew shape & monitor visualize
let monitor_hex_id = ['monitor-water', 'monitor-light', 'monitor-air', 'monitor-weather'];
let monitor_circle_id = [];

let length = 200;
let size = 36;


//繪圖資訊
export class Draw_Info {
    constructor(id, value, max, min) {
        this.id = id;
        this.value = value;
        this.max = max;
        this.min = min;
    };
}

export class Draw_Info_History {
    constructor(id, value, time, max, min) {
        this.id = id;
        this.value = value;
        this.time = time;
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
    d3.select('#' + info.id).select('svg').remove();
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

export function Make_HistoryChart(info, day) {
    let width = length * 3;
    let height = length * 2;
    let padding = {top: size * 1.5, right: size * 1.5, bottom: size * 1.5, left: size * 1.5};
    let point = [];
    let today = new Date(info.time[0]);
    let sevenDay = new Date();
    //抓取天數
    sevenDay.setDate(today.getDate() - Number(day));
    //標題記得改
    //清除後重劃
    d3.select('#' + info.id).select('svg').remove();


    //轉換為 point [[x,y],[x,y]...]
    for (let i = 0; i < info.value.length; i++) {
        point.push([info.time[i], info.value[i]])
    }
    //Ｘ軸 橫軸為 時間 回推24小時 最後一為 至 最新一為
    let xScale = d3
        .scaleUtc()
        .domain([
            sevenDay,
            today
        ])
        .range([
            0,
            width - padding.left - padding.right
        ]);
    // //Ｙ軸 該sensor的最大最小值 抓 最小
    let yScale = d3.scaleLinear()
        .domain([info.min, info.max])
        .range([height - padding.top - padding.bottom, 0]);
    //路徑
    let linePath = d3.line()
        .x(function (d) {
            return xScale(new Date(d[0]))
        })
        .y(function (d) {
            return yScale(d[1])
        })
        .curve(d3.curveBasis);
    // //開始畫畫
    let svg = d3.select('#' + info.id)
        .append('svg')
        .attr('width', width + 'px')
        .attr('height', height + 'px');
    let focus = svg.append("g")
        .attr("class", "focus")
        .style("display", "none");

    // svg.selectAll('*').remove();

    svg.append('g')
        .attr('class', 'axis')
        .attr('transform', 'translate(' + padding.left + ',' + (height - padding.bottom) + ')')
        .call(d3.axisBottom().scale(xScale))
        .selectAll("text")
        .attr('class', 'text-history')
        .style("text-anchor", "end")
        .attr("dx", "-.8em")
        .attr("dy", ".15em")
        .attr("transform", "rotate(-65)");
    svg.append('g')
        .attr('class', 'axis')
        .attr('transform', 'translate(' + padding.left + ',' + padding.top + ')')
        .call(d3.axisLeft().scale(yScale).ticks(5))
        .selectAll("text")
        .attr('class', 'text-history')
        .style("text-anchor", "end");
    svg.append('g')
        .append('path')
        .attr('class', 'line-path')
        .attr('transform', 'translate(' + padding.left + ',' + padding.top + ')')
        .attr('d', linePath(point))
        .attr('fill', 'none')
        .attr('stroke-width', 1)
        .attr('stroke', 'green');

    //原點
    focus.append("circle")
        .attr("r", 4.5);
    //方塊
    let rect = focus.append("rect")
        .attr("x", 9)
        .attr("dy", ".35em")
        .attr("fill", "yellow");
    //字
    let text = focus.append("text")
        .attr("x", 10)
        .attr("y", 10);


    // svg.append("rect")
    //     .attr("class", "overlay")
    //     .on("mouseover", function () {
    //         focus.style("display", null);
    //     })
    //     .on("mouseout", function () {
    //         focus.style("display", "none");
    //     })

    // svg.append('g')
    //     .selectAll('circle')
    //     .data(point)
    //     .enter()
    //     .append('circle')
    //     .attr('cx', function(d) {
    //         return xScale(d.x);
    //     })
    //     .attr('cy', function(d) {
    //         return yScale(d.y);
    //     })
    //     .attr('r', 5)
    //     .attr('transform', function(d){
    //         return 'translate(' + (xScale(d[0]) + padding.left) + ',' + (yScale(d[1]) + padding.top) + ')'
    //     })
    //     .attr('fill', 'green');
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

