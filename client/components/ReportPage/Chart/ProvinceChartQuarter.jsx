import React from 'react';
import {Bar, HorizontalBar} from 'react-chartjs-2'
import { Dimmer, Loader, Dropdown} from 'semantic-ui-react'

const areaOptions = [
  {
    key: 1,
    value: 1,
    text: 'Quý 1'
  },
  {
    key: 2,
    value: 2,
    text: 'Quý 2'
  },
  {
    key: 3,
    value: 3,
    text: 'Quý 3'
  },
  {
    key: 4,
    value: 4,
    text: 'Quý 4'
  },
];

const wrapStyles = {
  position: 'absolute',
  zIndex: 10,
  top: -20,
  right: -1
};

export default class ProvinceChartQuarter extends React.Component {
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
      activeQuarter: 1
    }
  }
  fetchData() {
    let {url} = this.state;
    if(!url) return false;
    url = `${url}&quarter=${this.state.activeQuarter}`;
    fetch(url)
      .then((response) => {
        return response.json();
      }).then((json) => {
        if(json.status && json.status == "error") {
          console.warn(json.message);
          return false;
        }
        this.setState({
          data: json.data,
          options: json.options,
          width: json.width,
          height: json.height,
          showChart: true,
          dataUpToDate: true,
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
      animation: {
        duration: 0,
        easing: 'linear'
      }
    };
    //console.log(options);
    return (
      <div className="ui segment">
        {this.state.dataUpToDate ? 
          <React.Fragment>
            <div className="quick-filter" style={wrapStyles}>
              <Dropdown 
                onChange={(event, data) => {
                  if(this.state.activeQuarter == data.value) return false;
                  this.setState({
                    activeQuarter: data.value,
                    dataUpToDate: null
                  })
                }}
                placeholder='Chọn Quý' 
                selection 
                value={this.state.activeQuarter}
                options={areaOptions} />
            </div>
            <HorizontalBar
              redraw={false}
              legend={legend}
              data={data}
              width={width || 300}
              height={height || 300}
              options={options}
            />
          </React.Fragment>
          : 
          <Loader active inline content="Loading"/>
          }
      </div>
    );
  }
}