import React from 'react'
import { Dropdown } from 'semantic-ui-react'
import {updateStateData} from 'actions'
import {connect} from 'react-redux'

class Product extends React.Component {  
  constructor(props) {
    super(props);
    this.state = {
      productOptions: [
        {
          key: 'all',
          value: 'all',
          text: 'Tất cả sản phẩm'
        }
      ]
    }
  }
  componentDidMount() {
    if(!this.props.mainState.products.length) {
      fetch(ISD_BASE_URL + 'product')
      .then((response) => {
        return response.json();
      }).then((json) => {
        if(json.status && json.status == "error") {
          console.warn(json.message);
          return false;
        }
        if(json.data) {
          let productOptions = json.data.map((product) => {
            return {
              key: product.product_id,
              value: product.product_id,
              text: product.name
            }
          });
          //update product list to store
          if(productOptions.length) {
            this.props.dispatch(updateStateData({
              products: productOptions
            }));
          }
          this.setState({productOptions: [...this.state.productOptions, ...productOptions]});

        }
      }).catch((error) => {
        console.log('parsing failed', error)
      });
    } else {
      this.setState({productOptions: [...this.state.productOptions, ...this.props.mainState.products]});
    }
  }
  render() {
    return (
      <Dropdown
        onChange={(event, data) => {
          if(this.props.mainState.filter.product == data.value) return false;
          this.props.dispatch(updateStateData({
            filter: {
              ...this.props.mainState.filter,
              product: data.value
            }
          }));
        }}
        placeholder='Chọn Sản Phẩm'  
        selection 
        search
        loading={this.state.productOptions.length == 1 ? true : false}
        value={this.props.mainState.filter.product}
        options={this.state.productOptions} />
    );
  }
}
export default connect((state) => {
  return {
    mainState: state.main.present,
  }
})(Product);