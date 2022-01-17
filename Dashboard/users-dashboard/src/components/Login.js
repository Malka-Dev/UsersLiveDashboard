import React, { useState } from 'react';
import axios from 'axios';
import { setUserSession } from '../Utils/Common';
import {useNavigate} from 'react-router-dom';


function Login(props) {
  const [loading, setLoading] = useState(false);
  const username = useFormInput('');
  const email = useFormInput('');
  const [error, setError] = useState(null);
  const [errors, setErrors] = useState(null);
  const navigation = useNavigate();

  const handleValidation = () => {
    //let fields = this.state.fields;
    let errors = {};
    let formIsValid = true;
    //Name
    if(!username.value){
      formIsValid = false;
      errors["name"] = "Cannot be empty";
    }

    if(typeof username.value !== "undefined"){
      if(!username.value.match(/^[a-zA-Z]+$/)){
        formIsValid = false;
        errors["name"] = "Only letters";
      }      	
    }

    if(typeof email.value !== "undefined"){
      let lastAtPos = email.value.lastIndexOf('@');
      let lastDotPos = email.value.lastIndexOf('.');

      if (!(lastAtPos < lastDotPos && lastAtPos > 0 && email.value.indexOf('@@') == -1 && lastDotPos > 2 && (email.value.length - lastDotPos) > 2)) {
        formIsValid = false;
        errors["email"] = "Email is not valid";
      }
    }

    setErrors(errors);
    return formIsValid;
  }
  // handle button click of login form
  const handleLogin = (e) => {
    e.preventDefault();
    if(handleValidation()){
      setError(null);
      setLoading(true);

      const jsonData = JSON.stringify({ userName: username.value, email: email.value });
      axios.post('http://localhost/api/index.php/user/signin', jsonData
      ).then(response => {
        setLoading(false);
        let data = JSON.parse(response.data);
        let resUser = [];
        if(data.message){
          setError(data.message);
        }
        else {
          setUserSession(data.token, data.user);
          navigation('/dashboard');
        }
      }).catch(error => {
        setLoading(false);
        if (error.response.status === 401) setError(error.response.data.message);
        else setError("Something went wrong. Please try again later.");
      });
    }else{
      console.log(errors);
    }
  }

  return (
    <div>
      <div className="background">
        <div className="shape"></div>
        <div className="shape"></div>
      </div>
    <div className='form'>
    <h3>Login Here</h3><br />
        <label>Username</label>
        <input type="text" {...username} autoComplete="new-password" />
        {errors && errors["name"] && <><small style={{ color: 'red' }}>{errors["name"]}</small><br /></>}<br />

        <label>Password</label>
        <input type="email" {...email} autoComplete="new-password" />
        {errors && errors["email"] && <><small style={{ color: 'red' }}>{errors["email"]}</small><br /></>}<br />

        <button value={loading ? 'Loading...' : 'Log In'} onClick={handleLogin} disabled={loading}>Log In</button>
    </div>
    </div>
  );
}

const useFormInput = initialValue => {
  const [value, setValue] = useState(initialValue);

  const handleChange = e => {
    setValue(e.target.value);
  }
  return {
    value,
    onChange: handleChange
  }
}

export default Login;