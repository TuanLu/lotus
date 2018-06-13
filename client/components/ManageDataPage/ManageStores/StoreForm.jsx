import React from 'react'
import { Loader, Form, Segment, Button} from 'semantic-ui-react'
import {Popconfirm} from 'antd'

class StoreForm extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      areas: [
        {key: 'empty', value: '', text: 'Chọn miền'},
        {key: 'b', value: 'b', text: 'Miền Bắc'},
        {key: 't', value: 't', text: 'Miền Trung'},
        {key: 'n', value: 'n', text: 'Miền Nam'},
      ],
      provinces: [],
      districts: [],
      area_id: '',
      province_id: '',
      district_id: ''
    }
  }
  componentDidMount() {
    fetch(ISD_BASE_URL + 'storeslocation')
      .then((response) => {
        return response.json();
      }).then((json) => {
        if(json.status && json.status == "error") {
          console.warn(json.message);
          return false;
        }
        if(json.data) {
          if(json.data.provinces.length && json.data.district.length) {
            this.setState({
              provinces: json.data.provinces,
              districts: json.data.district
            });
            
          }
        }
      }).catch((error) => {
        console.log('parsing failed', error)
      });
  }
  getPrivinceOptions() { 
    if(this.state.provinces.length) {
      let filterByArea = this.state.provinces;
      if(this.state.area_id) {
        filterByArea = this.state.provinces.filter((province) => province.area_code == this.state.area_id );  
      } 
      return filterByArea.map((province) => {
        return {
          key: province.code,
          value: province.code,
          text: province.name
        }
      });
    } else {
      return [{
        key: 'empty',
        value: '',
        text: 'Chọn miền trước'
      }]
    }
  }
  getDistrictOptions() {
    if(this.state.districts.length) {
      let filterByProvince = this.state.districts;
      if(this.state.province_id) {
        filterByProvince = this.state.districts.filter((district) => district.parent_code == this.state.province_id );
      }
      return filterByProvince.map((district) => {
        return {
          key: district.code,
          value: district.code,
          text: district.name
        }
      });
    } else {
      return [{
        key: 'empty',
        value: '',
        text: 'Chọn tỉnh trước'
      }]
    }
  }
  static getDerivedStateFromProps(nextProps, prevState) {
    return {
      ...nextProps.data
    };
  }
  handleChange = (e, { name, value }) => this.setState({ [name]: value })
  handleSubmit = () => {
    if(this.props.onSubmit) {
      let {store_id, name, address, owner, phone, district_id, area_id, province_id} = this.state;
      if(address == '' || district_id == '0' || district_id == '') {
        alert('Hãy nhập đầy đủ thông tin ở các trường có dấu (*)');
        return false;
      }
      let currentDistrict = this.state.districts.filter((district) => district.code == this.state.district_id);
      this.props.onSubmit({
        store_id,
        name,
        address,
        phone,
        owner,
        district_id,
        area_id,
        province_id,
        huyen: currentDistrict.length ? currentDistrict[0].name : ''//Pass this field to update district_name in store table
      });
    }
  }
  render() {
    let {store_id, name, address, phone, owner, district_id, area_id, province_id} = this.state;
    return (
      <Segment inverted color="teal" style={{
          textAlign: 'left', 
          width: 600,
          position: 'fixed',
          top: 50,
          right: 38,
          zIndex: 1000
        }}>
        {/* <pre>{JSON.stringify(this.state, null, 2)}</pre> */}
        <h2 style={{
          textAlign: 'center',
          textTransform: 'uppercase'
        }}>Thông tin nhà thuốc</h2>
        <Form onSubmit={this.handleSubmit}>
          <Form.Group widths={'equal'}>
            <Form.Input 
              value={store_id} 
              onChange={this.handleChange} 
              readOnly
              name="store_id" 
              label='Mã nhà thuốc' 
              placeholder='Tự động sinh mã' />
            <Form.Input 
              value={name} 
              onChange={this.handleChange}
              name="name" 
              label='Tên nhà thuốc' 
              placeholder='Tên nhà thuốc' />
          </Form.Group>
          <Form.TextArea 
            value={address}
            onChange={this.handleChange} 
            required 
            name="address" 
            label='Địa chỉ' 
            placeholder='Địa chỉ' />
          <Form.Group widths={'equal'}>
            <Form.Input 
              value={phone || ''}
              onChange={this.handleChange} 
              name="phone" 
              label='Điện thoại' 
              placeholder='Điện thoại' />
            <Form.Input 
              value={owner || ''}
              onChange={this.handleChange} 
              name="owner" 
              label='Chủ hiệu thuốc' 
              placeholder='Chủ hiệu thuốc' />
          </Form.Group>
          <Form.Group widths={'equal'}>
            <Form.Select 
              compact 
              name="area_id" 
              options={this.state.areas} 
              value={area_id}
              label="Miền" 
              onChange={this.handleChange} />
            <Form.Select 
              compact 
              name="province_id" 
              search 
              value={province_id}
              options={this.getPrivinceOptions()} 
              onChange={this.handleChange} 
              label="Tỉnh/TP"/>
            <Form.Select 
              required 
              search
              options={this.getDistrictOptions()} 
              onChange={this.handleChange} 
              compact 
              value={district_id}
              name="district_id" 
              label="Quận/Huyện"/>
          </Form.Group>
          
          <Button primary>Lưu nhà thuốc</Button>
          <Popconfirm
            placement="left"
            title="Bạn thật sự muốn huỷ?"
            onConfirm={(e) => {
              this.props.onCancel(e);
            }}
            >
            <Button type="button" color="orange">Huỷ</Button>
          </Popconfirm>
        </Form>
      </Segment>
    );
  }
}
export default StoreForm;