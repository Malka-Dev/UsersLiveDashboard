import React, { useState } from 'react';
import { getToken } from '../Utils/Common';
import axios from 'axios';

function UsersList(props) {
    let arrUsers = [];
    if(props.users) {
        Object.keys(props.users).forEach(function(key) {
            arrUsers.push(props.users[key]);
        });
    }

    const token = getToken();

    const [show, setShow] = useState(false);
    const [selectedData, setSelectedData] = useState({});

    const hanldeClick = (selectedRec) => {
        getUserDetails(selectedRec);
        setShow(true);
      };

      const getUserDetails = (user) => {
        axios.get(`http://localhost/api/index.php/user/list?id=${user.id}`, { headers: {Authorization : `Bearer ${token}`} }
        ).then(response => {
            setSelectedData(response.data);
        }).catch(error => {
        });
      }

    const hideModal = () => {
        setShow(false);
    };
    
  return (
    <div className="container">
    <table className="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Enterance time</th>
                <th>Last update time</th>
                <th>User IP</th>
            </tr>
        </thead>
        <tbody>
            {arrUsers && arrUsers.map(user =>
                <tr key={user.id} onClick={() => hanldeClick(user)}>
                    <td>{user?.name}</td>
                    <td>{user?.entrance_time}</td>
                    <td>{user?.last_update_time}</td>
                    <td>{user?.ip}</td>
                </tr>
            )}
        </tbody>
    </table>
    {show && <Modal user={selectedData} handleClose={hideModal} />}
</div>
  );
}

const Modal = ({ handleClose, user }) => {
    let arrUserData = [];
    if(user) {
        Object.keys(user).forEach(function(key) {
            arrUserData.push(user[key]);
        });
    }
    arrUserData = arrUserData[0];
    return (
      <div className="modal display-block">
        <section className="modal-content">
          <div className="App">
            <table className="table">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Email</th>
                  <th>User-Agent</th>
                  <th>Entrance time</th>
                  <th>Visits count</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                    <td>{arrUserData?.name}</td>
                    <td>{arrUserData?.email}</td>
                    <td>{arrUserData?.user_agent}</td>
                    <td>{arrUserData?.entrance_time}</td>
                    <td>{arrUserData?.visits_count}</td>
                </tr>
              </tbody>
            </table>
          </div>
          <button className='close-modal' onClick={handleClose}>close</button>
        </section>
      </div>
    );
  };

export default UsersList;