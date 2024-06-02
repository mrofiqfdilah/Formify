import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import axios from 'axios';

function Createformpage() {
  const navigate = useNavigate();

  const [formData, setFormData] = useState({
    name: '',
    slug: '',
    description: '',
    allowed_domains: '',
    limit_one_response: false
  });
  const [error, setError] = useState(null);

  const handleChange = (e) => {
    const { name, value, type, checked } = e.target;
    setFormData(prevState => ({
      ...prevState,
      [name]: type === 'checkbox' ? checked : value
    }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError(null);
  
    const domainsArray = formData.allowed_domains.split(',').map(domain => domain.trim());
  
    try {
      const response = await fetch('http://127.0.0.1:8000/api/v1/forms', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${localStorage.getItem('accessToken')}`,
        },
        body: JSON.stringify({
          name: formData.name,
          slug: formData.slug,
          description: formData.description,
          allowed_domains: domainsArray,
          limit_one_response: formData.limit_one_response,
        }),
      });
  
      const data = await response.json();
      console.log('Form created successfully:', data);
      navigate(`/home`);
  
    } catch (error) {
      setError('Server error: ' + error.message);
    }
  };
  

  return (
    <>
      <nav className="navbar navbar-expand-lg sticky-top bg-primary navbar-dark">
        <div className="container">
          <a className="navbar-brand" href="#">Formify</a>
          <ul className="navbar-nav ms-auto mb-2 mb-lg-0">
            <li className="nav-item">
              <a className="nav-link active" href="#">Administrator</a>
            </li>
            <li className="nav-item">
              <a href="/login" className="btn bg-white text-primary ms-4">Logout</a>
            </li>
          </ul>
        </div>
      </nav>

      <main>
        <div className="hero py-5 bg-light">
          <div className="container">
            <h2>Create Form</h2>
          </div>
        </div>

        <div className="py-5">
          <div className="container">
            <div className="row">
              <div className="col-md-6 col-lg-4">
                {error && <div className="alert alert-danger">{error}</div>}
                <form onSubmit={handleSubmit}>
                  <div className="form-group mb-3">
                    <label htmlFor="name" className="mb-1 text-muted">Form Name</label>
                    <input
                      type="text"
                      id="name"
                      name="name"
                      className="form-control"
                      value={formData.name}
                      onChange={handleChange}
                      required
                      autoFocus
                    />
                  </div>

                  <div className="form-group mb-3">
                    <label htmlFor="slug" className="mb-1 text-muted">Slug</label>
                    <input
                      type="text"
                      id="slug"
                      name="slug"
                      className="form-control"
                      value={formData.slug}
                      onChange={handleChange}
                      required
                    />
                  </div>

                  <div className="form-group mb-3">
                    <label htmlFor="description" className="mb-1 text-muted">Description</label>
                    <textarea
                      id="description"
                      name="description"
                      rows="4"
                      className="form-control"
                      value={formData.description}
                      onChange={handleChange}
                    ></textarea>
                  </div>

                  <div className="form-group mb-3">
                    <label htmlFor="allowed_domains" className="mb-1 text-muted">Allowed Domains</label>
                    <input
                      type="text"
                      id="allowed_domains"
                      name="allowed_domains"
                      className="form-control"
                      value={formData.allowed_domains}
                      onChange={handleChange}
                    />
                    <div className="form-text">Separate domains using comma ",". Ignore for public access.</div>
                  </div>

                  <div className="form-check form-switch mb-3">
                    <input
                      type="checkbox"
                      id="limit_one_response"
                      name="limit_one_response"
                      className="form-check-input"
                      checked={formData.limit_one_response}
                      onChange={handleChange}
                    />
                    <label className="form-check-label" htmlFor="limit_one_response">Limit to 1 response</label>
                  </div>

                  <div className="mt-4">
                    <button type="submit" className="btn btn-primary">Save</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </main>
    </>
  );
}

export default Createformpage;
