import React, { useEffect, useState } from 'react';
import { getUser, getToken, removeUserSession } from '../Utils/Common';
import UsersList from './UsersList';
import axios from 'axios';
import {useNavigate} from 'react-router-dom';
 
function Dashboard(props) {
  const user = getUser();
  const token = getToken();
  const navigation = useNavigate();

  const getUsers = () => {
    axios.get('http://localhost/api/index.php/user/list', { headers: {Authorization : `Bearer ${token}`} }
    ).then(response => {
      setUsers(response.data);
    }).catch(error => {
    });
  }

  const [users,setUsers] = useState(null);
  useEffect(() => {console.log('useEffect');
  getUsers();
    const interval = setInterval(() => {
      console.log('interval');
      getUsers();
    }, 3000);

    //beforeunload
    window.addEventListener('beforeunload', (e) => {
      handleLogout();
    });
    return () => {clearInterval(interval);
                  window.removeEventListener('beforeunload', (e) => {})}

  }, [])
  
  // handle logout event
  const handleLogout = () => {
    const jsonData = JSON.stringify({ id: user.id });
      axios.post('http://localhost/api/index.php/user/logout', jsonData, { headers: {Authorization : `Bearer ${token}`} }
      ).then(response => {
        removeUserSession();
      }).catch(error => {});
  }

  return (
    <div>
      <h4>Welcome {user.name}!</h4><br /><br />
      <UsersList users={users}/>
    </div>
  );
}
 
export default Dashboard;