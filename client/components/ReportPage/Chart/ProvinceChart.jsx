import React from 'react';
import {Bar, HorizontalBar} from 'react-chartjs-2'
import { Dimmer, Loader, Dropdown} from 'semantic-ui-react'

const areaOptions = [
  {
    key: 'b',
    value: 'b',
    text: 'Miền Bắc'
  },
  {
    key: 't',
    value: 't',
    text: 'Miền Trung'
  },
  {
    key: 'n',
    value: 'n',
    text: 'Miền Nam'
  },
  {
    key: 'cod',
    value: 'cod',
    text: 'COD'
  },
];

const wrapStyles = {
  position: 'absolute',
  zIndex: 10,
  top: -20,
  right: -1
};

export default class ProvinceChart extends React.Component {
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
      activeArea: 'b'
    }
  }
  fetchData() {
    let {url} = this.state;
    if(!url) return false;
    url = `${url}&area=${this.state.activeArea}`;
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
    };
    //console.log(options);
    return (
      <div className="ui segment">
        {this.state.dataUpToDate ? 
          <React.Fragment>
            <div className="quick-filter" style={wrapStyles}>
              <Dropdown 
                onChange={(event, data) => {
                  if(this.state.activeArea == data.value) return false;
                  this.setState({
                    activeArea: data.value,
                    dataUpToDate: null
                  })
                }}
                placeholder='Chọn Miền' 
                selection 
                value={this.state.activeArea}
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