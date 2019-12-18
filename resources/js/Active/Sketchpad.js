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

//水位圖（開關）改圖即可
export function WaterLevelChar(drowInfo){
    let imgUrl;

    drowInfo.data != drowInfo.max ? imgUrl =  './img/WaterLevOn.svg' : imgUrl = './img/WaterLevOff.svg';

    return imgUrl;
};


//燈泡更換 （開關）改圖即可
export function LightChange(drowInfo){
    let imgUrl;

    drowInfo.data >= drowInfo.max ? imgUrl =  './picture/LightOn.svg' : imgUrl = './picture/LightOff.svg';

    return imgUrl;
};

//風向 指針 變換方向
export function WindpointerChar(drowInfo){
    d3.select('#' + drowInfo.id).select('svg').remove();

    let data = drowInfo.value+360;
    let pointerData = [{x:length/2,y:30},{x:75,y:length/2 + 30},{x:length/2,y:length/2},{x:125,y:length/2 + 30}];

    let cx = length/2;
    let cy = length/2;
    let scaleLag = 8;           // N NW W WS S SE E EN
    let scaleSml = 2;           //每大格分 兩小格
    let radius      = length * 0.9 / 2 ;
    let indexGo = 0;
    let spinWay =['N','EN','E','ES','S','WS','W','WN']; //

    let majorDelta = 360 / scaleLag;        //大刻度之间的角度

    let svg = d3.select('#' + drowInfo.id)
                .append('svg')
                .attr('height',length +'px')
                .attr('width',length +'px')
                .attr('class','point_wind');

    let textStartMin = 4;
    let textStartMax = 45-textStartMin;
    let line = d3.line()
        .x(function (d) {
            return d.x;
        })
        .y(function (d) {
            return d.y;
        });
    // let pie = d3.layout.pie()
    //     .startAngle(0)
    //     .endAngle()
    svg.append('circle')            //底色黑底
        .attr('cx',cx)
        .attr('cy',cy)
        .attr('r',radius)
        .style('fill','#000');
    svg.append('circle')            //蓋底
        .attr('cx',cx)
        .attr('cy',cy)
        .attr('r',0.75 * radius)
        .style('fill','#fff');
    svg.append('path')              //不動針
        .attr('d',line(pointerData))
        .attr('y',0)
        .style('stroke','#fff')
        .style('stroke-width','1px')
        .style('fill',"#ff0000");
    svg.append('circle')              //針圓心
        .attr('cx',cx)
        .attr('cy',cy)
        .attr('r',0.05 * radius)
        .style('fill','#fff');

    //
    // //{     刻度
    for(let major = data; major <= data+315 ;major += majorDelta){
        let minMajor =  majorDelta /scaleSml ;
        let getStartPointLag = getPoint(major,0.85,80,cx,cy);

        for(let minMajorDe = major ; minMajorDe <= major+minMajor ; minMajorDe += minMajor){
            let getStartPoint = getPoint(minMajorDe,0.9,radius,cx,cy);
            let getEndPoint   = getPoint(minMajorDe,0.8,radius,cx,cy);

            if(minMajorDe % 45){
                svg.append('line')
                    .attr('x1',getStartPoint.Px)
                    .attr('y1',getStartPoint.Py)
                    .attr('x2',getEndPoint.Px)
                    .attr('y2',getEndPoint.Py)
                    .style('stroke',"#fff")
                    .style('stroke-width', "1px");
            }
        }

        svg.append('path')
            .attr('d',
                d3.arc()                     //架設路徑
                    .startAngle((major-textStartMin)/180*Math.PI)
                    .endAngle((major+textStartMax)/180*Math.PI)
                    .innerRadius(0.80 * radius)
                    .outerRadius(0.75 * radius)
            )
            .attr("transform",function(){
                return "translate(" + cx + "," + cy + ")"
            })
            .style("fill",'none')
            .attr('id','pathText_'+indexGo);

        svg.append('text')
            .append('textPath')
            .attr('link:href',"#pathText_"+indexGo)
            .attr('class','pointer_text')
            .style('fill','#fff')
            .text(spinWay[indexGo]);
        indexGo ++;
    }
    // //}
    // console.log(data);
};

//酸鹼值 pi
export function DoardChardot(drowInfo) {
    let size = 160 ;                                            //寬與長

    let max = 90;
    let min = -90 ;
    let meg = min ;
    let range = 15 ;

    let data = drowInfo.data;
    let radius      = size / 2 ;
    let svg = d3.select('#' + drowInfo.d3Scale).attr('height',size +'px').attr('width',size +'px');

    let color_bar = 0 ;
    let color_style = [
        '#ff0000',
        '#BB493E',
        '#A16B36',
        '#B9BB3E',
        '#ffff00',
        '#8ABF40',
        '#00ff7d',
        '#47c250',
        '#76c44f',
        '#328E2F',
        '#308991',
        '#339699',
        '#3749a4',
        '#0000ff',
        '#69349d',
    ];
    //角度表
    let bar_dela = [6,18,30,42,54,66,78,90,102,114,126,138,150,162,174];

    let pointerLine = d3.line()                                                         //曲線生長
        .x(function(d) {
            return d.x;
        })
        .y(function(d) {
            return d.y;
        });

    for(meg ; meg !== max ; meg+=12){
        svg.append('path')
            .attr('d',
                d3.arc()
                    .startAngle( (meg/180) * Math.PI)
                    .endAngle(  ((meg + 12 )/180) * Math.PI)
                    .innerRadius(0.5 * radius)
                    .outerRadius(0.85 * radius)
            )
            .attr("transform",function(){
                return "translate(" + 80 + "," + 80 + ")"
            })
            .style('stroke',"#fff")
            .style('stroke-radius','10px')
            .style('stroke-width', "1px")
            .style('z-index',1)
            .style('fill',color_style[color_bar])
            .attr('id','PHText_'+color_bar);
        color_bar ++;
    }
    svg.append('text')
        .attr('x',0)
        .attr('y',80)
        .style('fill','#000')
        .style('font-size',"12px")
        .text(0);
    svg.append('text')
        .attr('x',25)
        .attr('y',37)
        .attr('rotate',-45)
        .style('fill','#000')
        .style('font-size',"12px")
        .text(3);
    svg.append('text')
        .attr('x',78)
        .attr('y',10)
        .attr('rotate',0)
        .style('fill','#000')
        .style('font-size',"12px")
        .text(7);
    svg.append('text')
        .attr('x',120)
        .attr('y',25)
        .attr('rotate',45)
        .style('fill','#000')
        .style('font-size',"12px")
        .text(10);
    svg.append('text')
        .attr('x',148)
        .attr('y',80)
        .style('fill','#000')
        .style('font-size',"12px")
        .text(14);

    svg.append('text')                                  //單位
        .attr('x',80)
        .attr('y',45)
        .attr('dy',size*2/3)
        .attr('text-anchor',"middle")
        .text(drowInfo.unit + " " + data)
        .style('font-size',18 + "px")
        .style('fill',"#123123")
        .style('strok-width',"1px");

    svg.append('circle')                                //圓弧中心
        .attr('cx',80)
        .attr('cy',80)
        .attr('r',4)
        .style('fill','#fff')
        .style("stroke","#9DDF41")                              //邊界顏色
        .style("stroke-width","1px");                           //邊界粗度

    svg.append('g').attr('class','pointerCon')                       //指針群組
    let pointerStart  = [{x : 80,y : 80},{x :80-(70 * Math.cos((bar_dela[data]) / 180 * Math.PI)),y:80-(70 * Math.sin((bar_dela[data]) / 180 * Math.PI))}];

    let pointerConAni = svg.select(".pointerCon")               //指針畫布指向

    pointerConAni.append('path')                        //指針設置
        .attr('d',pointerLine(pointerStart))
        .style('fill','#dc3')
        .style("stroke", "#c63310") //轮廓的颜色
        .style('stroke-width','2px')
        .style('z-index',100)
        .style("fill-opacity", 2);  //填充的透明度

    pointerConAni.append('circle')                                //指針中心
        .attr('cx',80)
        .attr('cy',80)
        .attr('r',3)
        .style('fill','#F2FF83')
        .style("stroke","#9DDF41")                              //邊界顏色
        .style("stroke-width","0.5px")                          //邊界粗度
        .style('z-index',200);
}


export function Make_HistoryChart(info, day) {
    let width = length * 3;
    let height = length * 2;
    let padding = {top: size * 1.5, right: size * 1.5, bottom: size * 1.5, left: size * 1.5};
    let point = [];
    let dataValueCorr = {};
    let today = new Date();
    let lastDay = new Date();

    //今日的最後一筆
    today.setHours(23, 59, 59);
    //抓取天數
    if (Number(day) !== 1) {
        lastDay.setDate(today.getDate() - Number(day));
        lastDay.setHours(0, 0, 0, 0);
    } else {
        lastDay.setHours(0, 0, 0, 0);
    }
    //清除後重劃
    d3.select('#' + info.id).select('svg').remove();

    //轉換為 point [[x,y],[x,y]...] 這邊抓出符合的日期
    for (let i = 0; i < info.value.length; i++) {
        if (new Date(info.time[i]).getTime() >= lastDay.getTime())
            point.push({time: new Date(info.time[i]), value: info.value[i]});
        else
            break;
    }
    point.reverse();
    //Ｘ軸 橫軸為 時間 回推24小時 最後一為 至 最新一為
    let xScale = d3
        .scaleTime()
        .domain([
            lastDay,
            today
        ])
        .range([
            0,
            width - padding.left - padding.right
        ]);
    //Ｘ軸 用於 方塊 的 區域
    let rectScale = d3
        .scaleTime()
        .domain([
            lastDay,
            today
        ])
        .range([
            padding.left,
            width - padding.right
        ]);
    // //Ｙ軸 該sensor的最大最小值 抓 最小
    let yScale = d3.scaleLinear()
        .domain([info.min, info.max])
        .range([height - padding.top - padding.bottom, 0]);
    //Y軸 用於 方塊 的 區域
    let rectYScale = d3.scaleLinear()
        .domain([info.min, info.max])
        .range([height - padding.top, padding.bottom]);
    //路徑
    let linePath = d3.line()
        .x(function (d) {
            return xScale(new Date(d.time))
        })
        .y(function (d) {
            return yScale(d.value)
        });

    // //開始畫畫
    let svg = d3.select('#' + info.id)
        .append('svg')
        .attr('width', width + 'px')
        .attr('height', height + 'px');

    svg.append('g')
        .attr('class', 'axis')
        .attr('transform', 'translate(' + padding.left + ',' + (height - padding.bottom) + ')')
        .call(d3.axisBottom().scale(xScale).ticks(24))
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

    //用於點被看到時 有的反應
    let focus = svg.append("g")
        .attr("class", "focus")
        .style("display", "none");

    //原點
    focus.append("circle")
        .attr("r", 4.5);
    //方塊
    let rect = focus.append("rect")
        .attr("x", 10)
        .attr("dy", ".35rem")
        .attr('border-color', 'green')
        .attr('border-width', 1)
        .attr("fill-opacity", "0");
    //字
    let text = focus.append("text")
        .attr("x", 10)
        .attr("y", 10);

    let bisectDate = d3.bisector(d => d.time);

    svg.append("rect")
        .attr("class", "overlay")
        .attr("width", width - padding.left - padding.right)
        .attr("height", height - padding.top - padding.bottom)
        .attr('x', padding.left)
        .attr('y', padding.bottom)
        .on("mouseover", function () {
            focus.style("display", null);
        })
        .on("mouseout", function () {
            focus.style("display", "none");
        })
        .on("mousemove", mousemove);

    // console.log(point);
    function mousemove() {
        let contain = d3.mouse(this);
        let x = rectScale.invert(contain[0]);
        let i = bisectDate.right(point, x,1);
        let d0 = point[i - 1];
        let d1 = point[i];
        let d = (x - d0.time) > (d1.time - x) ? d1 : d0;
        //point time = x 的 數值 = y
        focus.attr("transform", "translate(" + rectScale(d.time) + "," + rectYScale(d.value) + ")");
        focus.select("text").text('數值：'+d.value)
            .attr('class','smValue')
            .append("tspan")
            .attr("x", 5).attr("dy", "1.5rem").attr('class','rectPlace').attr('text-align','center')
            .text(d3.timeFormat("%Y/%m/%d %H:%M")(d.time));
        let bbox = focus.select("text").node().getBBox();
        rect.attr("width", bbox.width + 4).attr("height", bbox.height + 4)
    }
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


//儀表板得到點
function getPoint(delta,bap,rad,cx,cy){             //角度
    let x = cx-(bap * rad * Math.cos(delta / 180 * Math.PI));
    let y = cy-(bap * rad * Math.sin(delta / 180 * Math.PI));

    return {Px:x,Py:y} ;
}

