import React from 'react'
import ReactTable from 'react-table'
import moment from 'moment'
import { Loader, Input, Segment, Button, Dropdown, Search, TextArea} from 'semantic-ui-react'
import SearchStore from './SearchStore'
import SearchDistrict from './SearchDistrict'
import UploadFile from './../../UploadFile'
import {Popconfirm} from 'antd'

class OrderForm extends React.Component {
  constructor(props) {
    super(props);
    this.renderEditableNumber = this.renderEditableNumber.bind(this);
    this.renderEditableText = this.renderEditableText.bind(this);
    this.renderEditableArea = this.renderEditableArea.bind(this);
    this.renderEditable = this.renderEditable.bind(this);
    this.updateRowData = this.updateRowData.bind(this);
    this.fetchData = this.fetchData.bind(this);
    this.state = {
      preOrderData: [],
      stores: [],
      products: [],
      deliveries: [],
      productIds: [],
      storeIds: [],
      deliveryIds: [],
      districtIds: [],
      districts: [],
      units: this.getUnitOptions(),
      unitIds: ['Hộp', 'Vỉ', 'Lọ'],
    };
  }
  getUnitOptions() {
    return [
      {key: 'Hộp', value: 'Hộp', text: 'Hộp'},
      {key: 'Vỉ', value: 'Vỉ', text: 'Vỉ'},
      {key: 'Lọ', value: 'Lọ', text: 'Lọ'},
    ];
  }
  getAreaOptions() {
    return [
      {key: 'Bắc', value: 'Bắc', text: 'Miền Bắc'},
      {key: 'Trung', value: 'Trung', text: 'Miền Trung'},
      {key: 'Nam', value: 'Nam', text: 'Miền Nam'},
    ];
  }
  renderEditableNumber(row) {
    return (
      <Input
        error={row.value == ''? true : false}
        type="number"
        min={0}
        fluid
        defaultValue={row.value}
        onBlur={e => {
          this.updateRowData(row, e.target.value);
        }}/>
    );
  }
  renderEditable(cellInfo) {
    return (
      <div
        className="editable-input"
        style={{ backgroundColor: "#fafafa" }}
        contentEditable
        suppressContentEditableWarning
        onBlur={e => {
          const data = [...this.state.preOrderData];
          data[cellInfo.index][cellInfo.column.id] = e.target.innerHTML;
          this.setState({ preOrderData: data });
        }}
        dangerouslySetInnerHTML={{
          __html: this.state.preOrderData[cellInfo.index][cellInfo.column.id]
        }}
      />
    );
  }
  renderEditableText(row) {
    return (
      <Input
        type="text"
        error={row.value == ''? true : false}
        required
        fluid
        defaultValue={this.state.preOrderData[row.index][row.column.id]}
        onBlur={e => {
          this.updateRowData(row, e.target.value);
        }}/>
    );
  }
  renderEditableArea(row) {
    return (
      <TextArea
        required
        defaultValue={row.value}
        onBlur={e => {
          this.updateRowData(row, e.target.value);
        }}/>
    );
  }
  updateRowData(row, value) {
    const preOrderData = [...this.state.preOrderData];
    //data[cellInfo.index][cellInfo.column.id] = e.target.innerHTML;
    preOrderData[row.index][row.column.id] = value;
    this.setState({ preOrderData });
  }
  validDataBeforeSave() {
    let {preOrderData} = this.state;
    //Check store_id,
    let isOk = true;
    if(preOrderData && preOrderData.length) {
      //console.log(preOrderData);
      for(let i = 0; i < preOrderData.length; i++) {
        //Valid store ID
        // if(this.state.storeIds.indexOf(preOrderData[i].store_id) == -1) {
        //   alert(`Mã hiệu thuốc [${preOrderData[i].store_id}] chưa tồn tại trong hệ thống! Bạn hãy thêm hiệu thuốc mới hoặc chọn lại mã đúng!`);
        //   isOk = false;
        //   break;
        // }
        //Valid product ID
        if(this.state.productIds.indexOf(preOrderData[i].product_id) == -1) {
          alert(`Mã sản phẩm [${preOrderData[i].product_id}] chưa tồn tại trong hệ thống! Bạn hãy thêm sản phẩm mới hoặc chọn lại mã sản phẩm đúng!`);
          isOk = false;
          break;
        }
        //Valid Time ID
        if(preOrderData[i].date == '' || !preOrderData[i].date) {
          alert(`Ngày mua không được để trống. Xin hãy nhập theo format ngay/thang/nam: VD: 24/05/2018`);
          isOk = false;
          break;
        }
      }
    }
    //Check new product

    //Check date

    return isOk;
  }
  updateDataToServer() {
    if(!this.validDataBeforeSave()) return false;
    let data = this.state.preOrderData;
    fetch(ISD_BASE_URL + 'addorders', {
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
        alert(`Đã cập nhật ${json.data} hoá đơn vào cơ sở dữ liệu!`);
        this.setState({
          preOrderData: []
        });
        if(this.props.onAddOrderComplete) {
          this.props.onAddOrderComplete();
        }
        
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
  static getDerivedStateFromProps(nextProps, prevState) {
    if(nextProps.data && nextProps.data.order_id) {
      //Edit order
      let orderItem = {
        ...nextProps.data,
        date: moment(nextProps.data.date).format('DD/MM/YYYY')
      }
      return {
        order_id: orderItem.order_id,
        preOrderData: [orderItem],
        dataUpToDate: null
      };
    }
    return true;
  }
  componentDidUpdate() {
    if(this.state.dataUpToDate === null && this.state.order_id) {
      this.fetchData(() => {
        let newPreOrderData = [...this.state.preOrderData];
        let storeInfo = this.state.stores.filter((store) => store.store_id == newPreOrderData[0].store_id);
        let districtInfo = this.state.districts.filter((district) => district.district_id == storeInfo[0].district_id);
        newPreOrderData[0] = {
          ...newPreOrderData[0],
          ...storeInfo[0],
          district_name: districtInfo[0].huyen
        }
        this.setState({
          dataUpToDate: true,
          preOrderData: newPreOrderData
        });
      });
    }
  }
  fetchData(callback) {
    fetch(ISD_BASE_URL + 'importOrderData')
    .then((response) => {
      return response.json();
    }).then((json) => {
      if(json.status && json.status == "error") {
        console.warn(json.message);
        return false;
      }
      if(json.data) {
        if(json.data.products 
          && json.data.products.length) {
          //Format data before update to state
          let productIds = [];
          let productList = json.data.products.map((product) => {
            productIds.push(product.product_id);
            return {
              key: product.product_id,
              value: product.product_id,
              text: product.name
            }
          });
          //Delivery 
          let deliveryIds = [];
          let deliverytList = json.data.deliveries.map((delivery) => {
            deliveryIds.push(delivery.delivery_id);
            return {
              key: delivery.delivery_id,
              value: delivery.delivery_id,
              text: delivery.name
            }
          });
          //Stores 
          let storeIds = [];
          let storeList = json.data.stores.map((store) => {
            storeIds.push(store.store_id);
            return store;
          });
          //Districts
          let districtIds = [];
          let districtList = json.data.districts.map((district) => {
            districtIds.push(district.district_id);
            return district;
          });
          this.setState({
            stores: storeList,
            products: productList,
            deliveries: deliverytList,
            districts: districtList,
            districtIds,
            productIds,
            storeIds,
            deliveryIds
          });
          callback && callback();
        } else {
          alert('Chưa có sản phẩm nào trong hệ thống! Bạn hãy nhập sản phẩm trước khi nhập đơn hàng!');
        }
      }
    }).catch((error) => {
      console.log('parsing failed', error)
    });
    //test data dev mode
    return false;
    fetch(ISD_BASE_URL + 'test')
    .then((response) => {
      return response.json();
    }).then((json) => {
      if(json.status && json.status == "error") {
        console.warn(json.message);
        return false;
      }
      if(json) {
        this.setState({
          preOrderData: json
        })
      }
    }).catch((error) => {
      console.log('parsing failed', error)
    });
  }
  componentDidMount() {
    this.fetchData();
  }
  getDefaultItemField() {
    return {
      'store_id': '',
      'name': '' ,
      'address': '',
      'product_id': '',
      'delivery_id': '',
      'date': '',
      'qty': '',
      'price': '',
      'unit': '',
      'district_id': '',
      'district_name': '',
      'name_address': '',
      'tdv': ''
    }
  }
  addNewOrder() {
    this.setState({
      preOrderData: this.state.preOrderData.concat(this.getDefaultItemField())
    });
  }

  render() {
    let {preOrderData} = this.state;
    return (
      <div className="order-add-form" style={{
        position: 'fixed',
        left: '50%',
        top: '50%',
        transform: 'translate(-50%, -50%)',
        width: '98%',
        background: '#fff',
        zIndex: 1000
      }}>
        {/* <pre>{JSON.stringify(this.state.preOrderData, null,2)}</pre> */}
        <div className="ui grid equal width">
          <div className="column left aligned">
            <h2 style={{textTransform: 'uppercase'}}>Form nhập đơn hàng</h2>
          </div>
          <div className="column right aligned">
            <div className="upload-btn primary" style={{position: 'relative'}}>
              <button style={{
                position: 'absolute',
                top: 0,
                left: 0,
                pointerEvents: 'none',
                marginTop: 10,
                zIndex: 1,
                width: '100%'
              }} type="button" className="fake-btn button ui primary">Import từ file excel</button>
              <UploadFile 
                url={ISD_BASE_URL + 'upload'}
                done={(response) => {
                  let resJson = JSON.parse(response);
                  if(resJson.status == "success" && resJson.data) {
                    this.setState({
                      preOrderData: resJson.data
                    })
                  }
                }}/>
            </div>
          </div>
          <div className="column right aligned">
            <button
              style={{marginTop: 10}} 
              onClick={() => {
                this.addNewOrder();
              }} 
              type="button" className="ui button primary">Thêm hoá đơn</button>
            <button
              style={{marginTop: 10}} 
              onClick={() => {
                this.updateDataToServer();
              }} 
              type="button" className="ui button teal">Lưu hoá đơn</button>
               <Popconfirm
                  placement="left"
                  title="Bạn thật sự muốn huỷ?"
                  onConfirm={() => {
                    this.setState({
                      preOrderData: []
                    })
                    this.props.onCancel();
                  }}
                  >
                    <button
                      style={{marginTop: 10}} 
                      type="button" className="ui button orange">Huỷ</button>
              </Popconfirm>
          </div>
        </div>
        <ReactTable
          columns={[
            {
              Header: "Thông tin nhà thuốc",
              columns: [
                {
                  Header: "Tìm nhà thuốc",
                  accessor: "store_id",
                  minWidth: 200,
                  Cell: (row) => {
                    //console.log(row.value);
                    return (
                        <div style={{position: 'relative'}} className={row.value != '' ? 'store-exitst' : ''}>
                          {row.value == '' ? <label className="ui teal floating left label tiny">Mới</label> : null}
                          <SearchStore 
                            onResultSelect={(store) => {
                              //Update this store to table state
                              let preOrderData = [...this.state.preOrderData];
                              preOrderData[row.index][row.column.id] = store.store_id;
                              preOrderData[row.index]["district_id"] = store.district_id;
                              if(store.district_id != 0) {
                                let findDistrict = this.state.districts.filter((district) => district.district_id == store.district_id);
                                if(findDistrict.length) {
                                  preOrderData[row.index]["district_name"] = findDistrict[0].huyen;
                                }
                              } else {
                                preOrderData[row.index]["district_id"] ='';
                                preOrderData[row.index]["district_name"] = '';
                              }
                              preOrderData[row.index]["address"] = store.address;
                              preOrderData[row.index]["name"] = store.name;
                              //console.log(store, row.index);
                              this.setState({ preOrderData });
                            }}  
                            storeId={this.state.storeIds.indexOf(row.value) == -1 ? '' : row.value}
                            source={this.state.stores}/>
                        </div>
                    );
                  },
                },
                {
                  Header: "Tên nhà thuốc",
                  accessor: "name",
                  minWidth: 200,
                  Cell: this.renderEditable
                },
                {
                  Header: "Địa chỉ (*)",
                  accessor: "address",
                  minWidth: 300,
                  Cell: this.renderEditable
                },
                {
                  Header: "Quận/Huyện/TP (*)",
                  accessor: "district_name",
                  minWidth: 200,
                  Cell: (row) => {
                    //console.log(row.value);
                    //let findDistrict = this.foundDistrictId(row, this.state.districts);
                    return (
                        <div 
                          className={!row.value ? 'store-item error' : ''}
                        >
                          {row.value ? 
                            <div>
                              <span>{row.value}</span>
                              <button
                                style={{float: 'right'}}
                                onClick={() => {
                                  const preOrderData = [...this.state.preOrderData];
                                  //Reset data
                                  preOrderData[row.index][row.column.id] = '';
                                  preOrderData[row.index]["district_id"] = '';
                                  this.setState({ preOrderData });
                                }} 
                                className="ui button icon basic compact circular tiny">
                                <i className="icon ui search"/>
                              </button>
                            </div>
                             : <SearchDistrict 
                            onResultSelect={(district) => {
                              const preOrderData = [...this.state.preOrderData];
                              preOrderData[row.index][row.column.id] = district.huyen;
                              preOrderData[row.index]["district_id"] = district.district_id;
                              this.setState({ preOrderData });
                            }}  
                            //districtId={this.state.districtIds.indexOf(row.value) == -1 ? '' : row.value}
                            source={this.state.districts}/>}
                          
                        </div>
                    );
                  },
                },
              ]
            },
            {
              Header: "Thông tin đơn hàng",
              columns: [
                {
                  Header: "Mã sản phẩm",
                  accessor: "product_id",
                  minWidth: 170,
                  Cell: (row) => {
                    
                    return (
                      <React.Fragment>
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
                      </React.Fragment>
                    );
                  }
                },
                {
                  Header: "Ngày bán",
                  accessor: "date",
                  minWidth: 150,
                  Cell: this.renderEditable
                },
                {
                  Header: "Số lượng",
                  accessor: "qty",
                  Cell: this.renderEditable
                },
                {
                  Header: "Đơn giá",
                  accessor: "price",
                  Cell: this.renderEditable
                },
                {
                  Header: "Đơn vị",
                  accessor: "unit",
                  Cell: (row) => {
                    return (
                      <Dropdown
                        error={this.state.unitIds.indexOf(row.value) == -1? true : false}
                        selection
                        fluid
                        compact
                        style={{zIndex: 0}}
                        options={this.state.units}
                        value={row.value}
                        onChange={(e, data) => {
                          this.updateRowData(row, data.value);
                        }}
                      />
                    );
                  }
                },
                {
                  Header: "Mã NPP",
                  accessor: "delivery_id",
                  minWidth: 100,
                  Cell: (row) => {
                    return (
                      <Dropdown
                        error={this.state.deliveryIds.indexOf(row.value) == -1? true : false}
                        selection
                        fluid
                        style={{zIndex: 0}}
                        search
                        options={this.state.deliveries}
                        onChange={(e, data) => {
                          this.updateRowData(row, data.value);
                        }}
                        value={row.value}
                      />
                    );
                  }
                },
                {
                  Header: "Mã TDV",
                  accessor: "tdv",
                  minWidth: 100,
                  Cell: this.renderEditable
                },
                {
                  Header: "Action",
                  accessor: "store_id",
                  filterable: false,
                  minWidth: 100,
                  Cell: (row) => {
                    return (
                      <React.Fragment>
                        <button
                          onClick={() => {
                            let title = row.row.name + ' ' + row.row.address;
                            if(!confirm('Bạn có thật sự muốn xoá bản ghi : ' + title)) return false;
                            const preOrderData = this.state.preOrderData.filter((order, index) => row.index != index);
                            this.setState({ preOrderData });
                          }} 
                          className="ui icon button red" role="button">
                          <i aria-hidden="true" className="remove icon"></i>
                        </button>
                      </React.Fragment>
                    );
                  }
                },
              ]
            },
          ]}
          filterable
          data={preOrderData}
          defaultPageSize={20}
          className="-striped -highlight"
          style={{
            height: "500px" // This will force the table body to overflow and scroll, since there is not enough room
          }}
        />
      </div>
    );
  }
}
export default OrderForm;