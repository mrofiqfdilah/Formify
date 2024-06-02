import React, { useState, useEffect } from "react";
import axios from "axios";

function Homepage() {
  const [forms, setForms] = useState([]);
  const accessToken = localStorage.getItem('accessToken');

  useEffect(() => {
   const fetchForms = async () => {
     try {
       const response = await axios.get('http://127.0.0.1:8000/api/v1/forms', {
         method: 'GET',
         headers: {
           'Content-Type': 'application/json',
           'Authorization': `Bearer ${accessToken}`
         }
       });
       setForms(response.data.form); // Ubah response.data menjadi response.data.form
     } catch (error) {
       console.error('Failed to fetch forms:', error);
       // Tambahkan penanganan error di sini jika diperlukan
     }
   };
 
   fetchForms();
 }, [accessToken]);
 

  return (
    <>
      <nav className="navbar navbar-expand-lg sticky-top bg-primary navbar-dark">
        <div className="container">
          <a className="navbar-brand" href="manage-forms.html">Formify</a>
          <ul className="navbar-nav ms-auto mb-2 mb-lg-0">
            <li className="nav-item">
              <a className="nav-link active" href="#">Administrator</a>
            </li>
            <li className="nav-item">
              <a href="index.html" className="btn bg-white text-primary ms-4">Logout</a>
            </li>
          </ul>
        </div>
      </nav>
      <main>
        <div className="hero py-5 bg-light">
          <div className="container">
            <a href="/createform" className="btn btn-primary">
              Create Form
            </a>
          </div>
        </div>

        <div className="list-form py-5">
          <div className="container">
            <h6 className="mb-3">List Form</h6>
            {forms.length > 0 ? (
              forms.map(form => ( 
                <a key={form.id} className="card card-default mb-3">
                  <div className="card-body">
                    <h5 className="mb-1">{form.name}</h5>
                    <small className="text-muted">{form.slug} (limit for {form.limit_one_response ? '1' : 'unlimited'} response)</small>
                  </div>
                </a>
              ))
            ) : (
              <p>No forms available</p>
            )}
          </div>
        </div>
      </main>
    </>
  );
}

export default Homepage;
