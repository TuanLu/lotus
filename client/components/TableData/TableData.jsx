import React from 'react';
import ReactTable from 'react-table'
import SmartControl from './SmartControl'
import {requestData} from 'ISD_API'

export default class TableData extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      data: this.props.orderData || [],
    };
  }
  render() {
    const { data} = this.state;
    return (
      <div>
        <SmartControl {...this.state}/>
        <ReactTable
          getTdProps={(state, rowInfo, column, instance) => {
            return {
              onClick: (e, handleOriginal) => {
                console.log("A Td Element was clicked!");
                console.log("it produced this event:", e);
                console.log("It was in this column:", column);
                console.log("It was in this row:", rowInfo);
                console.log("It was in this table instance:", instance);
        
                // IMPORTANT! React-Table uses onClick internally to trigger
                // events like expanding SubComponents and pivots.
                // By default a custom 'onClick' handler will override this functionality.
                // If you want to fire the original onClick handler, call the
                // 'handleOriginal' function.
                if (handleOriginal) {
                  handleOriginal();
                }
              }
            };
          }}
          filterable
          data={data}
          columns={[
            {
              Header: "Nhà thuốc",
              columns: [
                {
                  Header: "Mã KH",
                  accessor: "B",
                  maxWidth: 200
                },
                {
                  Header: "Tên nhà thuốc",
                  accessor: 'F'
                }
              ]
            },
            {
              Header: "Đơn Hàng",
              columns: [
                {
                  Header: "Ngày bán",
                  accessor: "A"
                },
                {
                  Header: "Mã Sản Phẩm",
                  accessor: "C"
                },
                {
                  Header: "Số lượng",
                  accessor: "D"
                },
                {
                  Header: "Đơn giá",
                  accessor: "J"
                },
              ]
            },
            {
              Header: "Địa chỉ",
              columns: [
                {
                  Header: "Quận/TP/Huyện",
                  accessor: "M"
                },
                {
                  Header: "Tỉnh",
                  accessor: "N"
                },
                {
                  Header: "Miền",
                  accessor: "O"
                },
              ]
            },
          ]}
          data={data}
          filterable
          onFilteredChange={(data) => {
            console.log(data);
          }}
          defaultPageSize={10}
          className="-striped -highlight"
        />
      </div>
    );
  }
}