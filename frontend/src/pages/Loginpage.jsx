import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import axios from 'axios';

function Loginpage()
{
    const navigate = useNavigate();

    const [formData, setFormData] = useState({
      email: '',
        password: ''
    });

    const handleChange = (e) => {
        const { name, value } = e.target;
        setFormData(prevState => ({
            ...prevState,
            [name]: value
        }));
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        try {
           
            const response = await axios.post('http://127.0.0.1:8000/api/v1/auth/login', formData);
            console.log(response.data); // Handle success response here

      
localStorage.setItem('accessToken', response.data.user.accessToken);
console.log(response.data.user.accessToken);

navigate('/home')
        } catch (error) {
            console.error('Login failed:', error); // Handle error here
        }
    };

    return (
       <>
<main>
      <section class="login">
         <div class="container">
            <div class="row justify-content-center">
               <div class="col-lg-5 col-md-6">
                  <h1 class="text-center mb-4">Formify</h1>
                  <div class="card card-default">
                     <div class="card-body">
                        <h3 class="mb-3">Login</h3>
                        
                        <form onSubmit={handleSubmit}> 
                          
                           <div class="form-group my-3">
                              <label for="email" class="mb-1 text-muted">Email Address</label>
                              <input type="email" id="email" name="email" value={formData.email} onChange={handleChange} class="form-control"  autofocus />
                           </div> 

                          
                           <div class="form-group my-3">
                              <label for="password" class="mb-1 text-muted">Password</label>
                              <input type="password" id="password" name="password" value={formData.password} onChange={handleChange} class="form-control" />
                           </div>
                           
                           <div class="mt-4">
                              <button type="submit" class="btn btn-primary">Login</button>
                           </div>
                        </form>

                     </div>
                  </div> 
               </div>
            </div>
         </div>
      </section>
   </main>
       </>
    )
}

export default Loginpage