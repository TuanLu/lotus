import React from 'react';
import {Loader, Input, Dropdown} from 'semantic-ui-react'
import ReactTable from 'react-table'
const range = (start, end) => (
  Array.from(Array(end - start + 1).keys()).map(i => i + start)
);

export default class ManagePlan extends React.Component {
  constructor(props) {
    super(props);
    this.fetchData = this.fetchData.bind(this);
    this.planColumns = this.planColumns.bind(this);
    this.renderEditableNumber = this.renderEditableNumber.bind(this);
    this.updateRowData = this.updateRowData.bind(this);
    this.addNewPlan = this.addNewPlan.bind(this);
    this.savePlan = this.savePlan.bind(this);
    this.state = {
      dataUpToDate: null,
      url: this.props.url,
      datasource: [],
      products: []
    }
  }
  updateRowData(row, value) {
    const datasource = [...this.state.datasource];
    datasource[row.index][row.column.id] = value;
    this.setState({ datasource });
  }
  renderEditableNumber(row) {
    return (
      <Input
        error={row.value == ''? true : false}
        type="number"
        min={0}
        fluid
        defaultValue={row.value || 0}
        onChange={e => {
          this.updateRowData(row, e.target.value);
        }}/>
    );
  }
  fetchData() {
    let {url} = this.state;
    if(!url) return false;
    fetch(url)
      .then((response) => {
        return response.json();
      }).then((json) => {
        if(json.status && json.status == "error") {
          console.warn(json.message);
          return false;
        }
        let newState = {};
        if(json.plans) {
          newState.datasource = json.plans;
        }
        if(json.products) {
          //setup product dropdown 
          newState.products = json.products.map((product) => {
            return {
              key: product.product_id,
              value: product.product_id,
              text: product.name
            }
          });
        }
        this.setState({
          dataUpToDate: true,
          ...newState
        });
      }).catch((error) => {
        console.log('parsing failed', error)
      });
  }
  planColumns() {
    let columns = range(1,52).map((item, index) => {
      return {
        Header: `T${item}`,
        accessor: `w${item}`,
        maxWidth: 100,
        Cell: this.renderEditableNumber
      }
    });
    return columns;
  }
  addNewPlan() {
    let planData = {
      plan_no: '',
      year: (new Date).getFullYear(),
      product_id: '',
      ...range(1,52).map((week) => {`w${week}`} )
    }
    this.setState({
      datasource: this.state.datasource.concat(planData)
    })
  }
  validDataBeforeSave() {
    //Make sure plan of each product and year is unique
    let planKeys = this.state.datasource.map((plan) => {
      return `${plan.year}_${plan.product_id}`
    });
    //Check planKeys has duplicate item or not 
    const count = names => 
      names.reduce((a, b) => 
        Object.assign(a, {[b]: (a[b] || 0) + 1}), {})
    const duplicates = dict => 
      Object.keys(dict).filter((a) => dict[a] > 1)
    let isDuplicated = duplicates(count(planKeys));
      
    if(isDuplicated.length) {
      alert('Có sản phẩm bị trùng kế hoạch: ' + isDuplicated[0]);
      return false;
    }
    return true;
  }
  savePlan() {
    if(!this.validDataBeforeSave()) return false;
    let data = this.state.datasource;
    fetch(ISD_BASE_URL + 'updateplan', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(data)
    })
    .then((response) => {
      return response.json()
    }).then((json) => {
      //Update table data 
      if(json.data) {
        alert(`Đã cập nhật kế hoạch vào cơ sở dữ liệu!`);
        
      } else {
        //Might not update anything
        alert('Dữ liệu chưa được lưu vào hệ thống. Xin kiểm tra lại');
        // this.setState({
        //   showForm: true
        // })
      }
      
      
    }).catch((ex) => {
      console.log('parsing failed', ex)
    });
  }
  
  componentDidMount() {
    this.fetchData();
  }
  render() {
    let {datasource} = this.state;
    return (
      <div className="ui segment order-add-form">
        {/* <pre>{JSON.stringify(this.state.datasource, null, 2)}</pre> */}
        {this.state.dataUpToDate ? 
          <React.Fragment>
            <div className="ui grid equal width">
              <div className="column left aligned">
                <h2 style={{textTransform: 'uppercase'}}>Kế hoạch theo từng tuần trong năm</h2>
              </div>
              <div className="column right aligned">
                <button onClick={() => {
                  this.addNewPlan();
                }} type="button" className="ui button primary">Thêm kế hoạch</button>
                <button onClick={() => {
                  //Save all state to server
                  this.savePlan();
                }} type="button" className="ui button teal">Lưu thay đổi</button>
              </div>
            </div>
            <ReactTable
              getTdProps={(state, rowInfo, column, instance) => {
                return {
                  onClick: (e, handleOriginal) => {
                    if (handleOriginal) {
                      handleOriginal();
                    }
                  }
                };
              }}
              columns={[
                {
                  Header: "Thông tin kế hoạch",
                  columns: [
                    {
                      Header: `Năm`,
                      accessor: 'year',
                      Cell: this.renderEditableNumber
                    },
                    {
                      Header: `Sản phẩm`,
                      accessor: 'product_id',
                      minWidth: 200,
                      Cell: (row) => {
                        return (
                          <Dropdown
                            fluid
                            selection
                            style={{zIndex: 100}}
                            search
                            options={this.state.products}
                            value={row.value}
                            onChange={(e, data) => {
                              this.updateRowData(row, data.value);
                            }}
                          />
                        );
                      }
                    },
                  ]
                },
                {
                  Header: "Kế hoạch từng tuần trong năm",
                  columns: this.planColumns()
                },
              ]}
              data={datasource}
              filterable
              defaultPageSize={20}
              className="-striped -highlight"
              style={{
                
              }}
            />
          </React.Fragment>
          : 
          <Loader active inline content="Loading"/>
          }
      </div>
    );
  }
}