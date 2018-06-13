import React from 'react';
import ReactTable from 'react-table'
import StoreForm from './StoreForm'
import {Popconfirm} from 'antd'

export default class TableData extends React.Component {
  constructor(props) {
    super(props);
    this.handleRowClick = this.handleRowClick.bind(this);
    this.fetchData = this.fetchData.bind(this);
    this.state = {
      data: [],
      currentStore: {
        ...this.getResetDataField()
      },
      showForm: false
    };
  }
  getResetDataField() {
    return {
      store_id: '',
      name: '',
      address: '',
      phone: '',
      owner: '',
      district_id: ''
    }
  }
  handleRowClick(data) {
    this.setState({
      currentStore: data,
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
            if(store.store_id == json.data.store_id) {
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
          currentStore: {
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
  deleteStore(storeId) {
    fetch(ISD_BASE_URL + `deletestore/${storeId}`, {
      method: 'GET',
    })
    .then((response) => {
      return response.json()
    }).then((json) => {
      //Update table data 
      if(json.data) {
        let newData = this.state.data.filter((store) => store.store_id != json.data);
        this.setState({
          data: newData,
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
  fetchData() {
    let {url} = this.props;
    if(!url) return false;
    fetch(url)
      .then((response) => {
        return response.json();
      }).then((json) => {
        if(json.status && json.status == "error") {
          console.warn(json.message);
          return false;
        }
        if(json.data && json.data.length) {
          this.setState({
            dataUpToDate: true,
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
    const { data, currentStore} = this.state;
    return (
      <div>
        <div className="ui grid equal width">
          <div className="column left aligned">
            <h2 style={{textTransform: 'uppercase'}}>Danh sách các nhà thuốc</h2>
          </div>
          <div className="column right aligned">
            <button onClick={() => {
              this.setState({
                showForm: true,
                currentStore: {
                  ...this.getResetDataField()
                }
              });
            }} type="button" className="ui button primary">Thêm hiệu thuốc</button>
          </div>
        </div>
        {this.state.showForm ?
          <StoreForm 
            data={currentStore}
            onSubmit={(data) => {
              this.updateDataToServer(data);
            }}
            onCancel={(e) => {
              this.setState({
                showForm: false,
                currentStore: {
                  ...this.getResetDataField()
                }
              })
            }}
          />
          : null}
        <ReactTable
          columns={[
            {
              Header: "Thông tin nhà thuốc",
              columns: [
                {
                  Header: "Mã KH",
                  accessor: "store_id",
                  maxWidth: 100
                },
                {
                  Header: "Tên nhà thuốc",
                  accessor: 'name',
                  filterMethod: this.customFilter
                },
                {
                  Header: "Địa chỉ",
                  accessor: 'address',
                  filterMethod: this.customFilter
                },
                {
                  Header: "Điện thoại",
                  accessor: 'phone'
                },
                {
                  Header: "Chủ",
                  accessor: 'owner'
                },
                {
                  Header: "Mã Quận/Huyện (*)",
                  accessor: 'huyen',
                  filterMethod: this.customFilterDistrict,
                  Cell: row => (
                    <div className={`${row.original.district_id == 0 ? 'ui warning message' : ''}`} style={{
                      padding: 2
                    }}>
                      {/* <pre>{JSON.stringify(row, null, 2)}</pre> */}
                      {row.original.district_id != 0 ? row.original.huyen : 'Chọn mã quận/huyện'}
                    </div>
                  )
                },
              ]
            },
            {
              Header: "Actions",
              columns: [
                {
                  Header: "Sửa | Xoá",
                  accessor: "store_id",
                  filterable: false,
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
                            this.deleteStore(row.value);
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