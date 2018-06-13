import React from 'react';
import ReactTable from 'react-table'
import OrderForm from './OrderForm'
import moment from 'moment'
import {Popconfirm} from 'antd'
import {getTokenHeader} from 'ISD_API'

export default class TableData extends React.Component {
  constructor(props) {
    super(props);
    this.handleRowClick = this.handleRowClick.bind(this);
    this.refreshOrderData = this.refreshOrderData.bind(this);
    this.fetchData = this.fetchData.bind(this);
    this.state = {
      data: [],
      currentOrder: {
        ...this.getResetDataField()
      },
      showForm: false
    };
  }
  fetchData() {
    let {url} = this.props;
    if(!url) return false;
    fetch(url, {
      headers: getTokenHeader()
    })
      .then((response) => {
        return response.json();
      }).then((json) => {
        if(json.status && json.status == "error") {
          console.warn(json.message);
          return false;
        }
        if(json.data && json.data.length) {
          this.setState({
            //dataUpToDate: true,
            data: json.data
          });
        }
      }).catch((error) => {
        console.log('parsing failed', error)
      });
  }
  getResetDataField() {
    return {
      order_id: '',
      name: '',
      address: '',
      phone: '',
      owner: '',
      district_id: ''
    }
  }
  handleRowClick(data) {
    this.setState({
      currentOrder: data,
      showForm: true
    });
  }
  updateDataToServer(data) {
    fetch(ISD_BASE_URL + 'updatestore', {
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
        let newData;
        if(json.newRecord) {
          newData = this.state.data.concat(json.data);
        } else {
          newData = this.state.data.map((store) => {
            if(store.order_id == json.data.order_id) {
              return {
                ...store,
                ...json.data,
              }
            }
            return store;
          });
        }
        
        this.setState({
          data: newData,
          showForm: false,
          currentOrder: {
            ...this.getResetDataField()
          }
        });
      } else {
        //Might not update anything
        this.setState({
          showForm: false
        })
      }
    }).catch((ex) => {
      console.log('parsing failed', ex)
    });
  }
  deleteOrder(orderId) {
    fetch(ISD_BASE_URL + `deleteorder/${orderId}`, {
      method: 'get',
    })
    .then((response) => {
      return response.json()
    }).then((json) => {
      //Update table data 
      if(json.data) {
        let newData = this.state.data.filter((store) => store.order_id != json.data);
        this.setState({
          data: newData,
          showForm: false,
          currentOrder: {
            ...this.getResetDataField()
          }
        });
      }
    }).catch((ex) => {
      console.log('parsing failed', ex)
    });
  }
  customFilter(filter, row) {
    if(row[filter.id] && row[filter.id] != '' && filter.value != '') {
      return row[filter.id].toLowerCase().includes(filter.value.toLowerCase());
    }
    return false;
  }
  customFilterDistrict(filter, row) {
    if(row[filter.id] && row[filter.id] != '' && filter.value != '') {
      return row[filter.id].toLowerCase().includes(filter.value.toLowerCase());
    }
    return false;
  }
  refreshOrderData() {
    fetch(ISD_BASE_URL + 'orders')
    .then((response) => {
      return response.json();
    }).then((json) => {
      if(json.status && json.status == "error") {
        console.warn(json.message);
        return false;
      }
      if(json.data && json.data.length) {
        this.setState({
          data: json.data
        });
      }
    }).catch((error) => {
      console.log('parsing failed', error)
    });
  }
  componentDidMount() {
    this.fetchData();
  }
  render() {
    const { data, currentOrder} = this.state;
    return (
      <div>
        <div className="ui grid equal width">
          <div className="column left aligned">
            <h2 style={{textTransform: 'uppercase'}}>Danh sách đơn hàng</h2>
          </div>
          <div className="column right aligned">
            <button onClick={() => {
              this.setState({
                showForm: true,
                currentOrder: {
                  ...this.getResetDataField()
                }
              });
            }} type="button" className="ui button primary">Nhập đơn hàng</button>
          </div>
        </div>
        {this.state.showForm ?
          <OrderForm 
            data={currentOrder}
            onAddOrderComplete={() => {
              this.setState({
                showForm: false
              });
              //Refresh Table Data
              this.refreshOrderData();
            }}
            onCancel={(e) => {
              this.setState({
                showForm: false,
              })
            }}
          />
          : null}
        <ReactTable
          columns={[
            {
              Header: "Mã hoá đơn",
              columns: [
                {
                  Header: "OrderID",
                  accessor: "order_id",
                  maxWidth: 100
                },
                {
                  Header: "Mã nhà thuốc",
                  accessor: "store_id",
                  maxWidth: 100
                },
                {
                  Header: "Mã sản phẩm",
                  accessor: "product_id",
                  maxWidth: 100
                },
                {
                  Header: "Mã NPP",
                  accessor: "delivery_id",
                  maxWidth: 100
                },
              ]
            },
            {
              Header: "Thông tin đơn hàng",
              columns: [
                {
                  Header: "Ngày bán",
                  accessor: "date",
                  //Cell: (row) => moment(row.date).format('DD/MM/YYYY')
                },
                {
                  Header: "Số lượng",
                  accessor: "qty",
                },
                {
                  Header: "Đơn vị",
                  accessor: "unit",
                },
                {
                  Header: "Đơn giá",
                  accessor: "price",
                },
                {
                  Header: "TDV",
                  accessor: "tdv",
                },
              ]
            },
            {
              Header: "Actions",
              columns: [
                {
                  Header: "Sửa | Xoá",
                  filterable: false,
                  accessor: "order_id",
                  Cell: (row) => {
                    return (
                      <React.Fragment>
                        <button
                          onClick={() => {
                            this.handleRowClick(row.original);
                          }}
                          className="ui icon button teal tiny" role="button">
                          <i aria-hidden="true" className="edit icon"></i>
                        </button>
                        <Popconfirm
                          placement="left"
                          okType="danger"
                          title="Bạn thật sự muốn xoá?"
                          onConfirm={() => {
                            this.deleteOrder(row.value);
                          }}
                          >
                           <button
                            className="ui icon button red tiny" role="button">
                            <i aria-hidden="true" className="remove icon"></i>
                          </button>
                        </Popconfirm>
                      </React.Fragment>
                    );
                  }
                },
              ]
            },
          ]}
          data={data}
          filterable
          defaultPageSize={20}
          className="-striped -highlight"
        />
      </div>
    );
  }
}