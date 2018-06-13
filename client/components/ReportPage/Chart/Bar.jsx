import React from 'react';
import {Bar, HorizontalBar} from 'react-chartjs-2'
import { Dimmer, Loader} from 'semantic-ui-react'

export default class BarChart extends React.Component {
  constructor(props) {
    super(props);
    this.fetchData = this.fetchData.bind(this);
    this.state = {
      data: {},
      options: {},
      legend: {
        
      },
      showChart: false,
      url: this.props.url,
      dataUpToDate: null,
      noData: false
    }
  }
  fetchData() {
    let {url} = this.state;
    if(!url) return false;
    fetch(url)
      .then((response) => {
        return response.json();
      }).then((json) => {
        if(json.status && json.status == "error") {
          this.setState({noData: true, dataUpToDate: true});
          return false;
        }
        this.setState({
          data: json.data,
          options: json.options,
          width: json.width,
          height: json.height,
          showChart: true,
          dataUpToDate: true,
          noData: false,
          legend: json.legend || {}
        });
      }).catch((error) => {
        console.log('parsing failed', error)
      });
  }
  static getDerivedStateFromProps(nextProps, prevState) {
    if(nextProps.url != prevState.url) {
      return {
        url: nextProps.url,
        dataUpToDate: null
      }
    }
    return null;
  }
  componentDidMount() {
    this.fetchData();
    
    // Chart.pluginService.register({
    //   beforeRender: function (chart) {
    //     if (chart.config.options.showAllTooltips) {
    //       chart.pluginTooltips = [];
    //       chart.config.data.datasets.forEach(function (dataset, i) {
    //         chart.getDatasetMeta(i).data.forEach(function (sector, j) {
    //           chart.pluginTooltips.push(new Chart.Tooltip({
    //             _chart: chart.chart,
    //             _chartInstance: chart,
    //             _data: chart.data,
    //             _options: chart.options.tooltips,
    //             _active: [sector]
    //           }, chart));
    //         });
    //       });
    //       // turn off normal tooltips
    //       chart.options.tooltips.enabled = false;
    //     }
    //   }, afterDraw: function (chart, easing) {
    //     if (chart.config.options.showAllTooltips) {
    //       // we don't want the permanent tooltips to animate, so don't do anything till the animation runs atleast once
    //       if (!chart.allTooltipsOnce) {
    //         if (easing !== 1) return;
    //         chart.allTooltipsOnce = true;
    //       }
    //       chart.options.tooltips.enabled = true;
    //       Chart.helpers.each(chart.pluginTooltips, function (tooltip) {
    //         tooltip.initialize();
    //         tooltip.update(); // we don't actually need this since we are not animating tooltips
    //         tooltip.pivot();
    //         tooltip.transition(easing).draw();
    //       });
    //       chart.options.tooltips.enabled = false;
    //     }
    //   }
    // });
  }
  componentDidUpdate(prevProps, prevState) {
    if (this.state.dataUpToDate === null) {
      this.fetchData();
    }
  }
  render() {
    let {data, options, width, height, legend} = this.state;
    options = {
      ...options,
      tooltips: {
        //mode: 'dataset',
        //intersect: false,
        // enabled : false,
        // callbacks: {
        //   label: function(tooltipItem) {
        //       return tooltipItem.yLabel;
        //   },
        //   title: function(tooltipItem) {
        //     return '';
        //   },
        // },
        // backgroundColor: 'gold',
        // // //titleFontSize: 16,
        // // titleFontColor: '#0066ff',
        // bodyFontColor: '#006699',
        // bodyFontSize: 12,
        // bodyFontStyle: 'bold',
        // xPadding: 4,
        // yPadding: 4,
        // //caretSize: 2,
        // caretPadding: 0,
        // //cornerRadius: 0,
        // displayColors: false
      },
    };
    //console.log(options);
    if(this.state.noData) {
      return (
        <div className="ui segment">
          <div className="ui message info">Chưa có dữ liệu</div>
        </div>
      );
    }
    return (
      <div className="ui segment">
        {this.state.dataUpToDate ? 
          <React.Fragment>
          {this.props.type == "horizontalBar" ? 
            <HorizontalBar
              redraw={false}
              legend={legend}
              data={data}
              width={width || 300}
              height={height || 300}
              options={options}
            />
            : 
            <Bar
              redraw={false}
              legend={legend}
              data={data}
              width={width || 300}
              height={height || 300}
              options={options}
            />
          }
          </React.Fragment>
          : 
          <Loader active inline content="Loading"/>
          }
      </div>
    );
  }
}