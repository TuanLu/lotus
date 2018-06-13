import React from 'react'
import { Progress } from 'semantic-ui-react'

export default class UploadFile extends React.Component {
  constructor(props) {
    super(props);
    this.upload = this.upload.bind(this);
    this.chooseFile = this.chooseFile.bind(this);
    this.state = {
      progressing: 0,
      upload: false,
      error: false,
      errorMessage: this.props.errorMessage || 'Xin lỗi! file chưa được upload thành công!'
    }
  }
  upload(formData, url) {
    let self = this;
    var xhr = new XMLHttpRequest();
    xhr.open('POST', url, true);
    this.setState({upload: true});
    xhr.upload.onprogress = function(e) {
      if (e.lengthComputable) {
        var percentComplete = (e.loaded / e.total) * 100;
        self.setState({
          progressing: parseInt(percentComplete)
        });
      }
    };
    xhr.onload = function() {
      if (this.status == 200) {
        self.setState({upload: false, progressing: 0, error: false});
        if(self.props.done) {
          self.props.done(this.response);
          let responseJSON = JSON.parse(this.response);
          if(responseJSON.status == "error") {
            self.setState({
              error: true,
              errorMessage: responseJSON.message || this.state.errorMessage
            })
          }
        }
      };
    };
    xhr.onerror = function() {
      //Still show progress bar
      self.setState({upload: true, progressing: 1, error: true});
    }
    xhr.send(formData);
  }
  chooseFile(e) {
    var files = e.target.files;
    var data = new FormData();
    data.append('filename', files[0]);
    this.upload(data, this.props.url);
  }
  render() {
    return (
      <div className="file-upload" style={this.props.style}>
        <input type="file" onChange={(e) => this.chooseFile(e)}/>
        {this.state.upload ? 
        <Progress 
          style={{marginTop: 10}}
          percent={this.state.progressing} 
          color='olive' 
          progress 
          indicating /> : null}
        {this.state.error? <div className="ui message warning">{this.state.errorMessage}</div> : null}
      </div>
    );
  }
}