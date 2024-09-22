import React, { useState, useEffect } from "react";
import "./style.css"; // Include your external stylesheet

const PatientModule = () => {
    // Dummy patient data
    const todayPatients = [
        { time: "10:30", name: "Abdul", age: 25, problem: "Bleeding gums", gender: "Male", patientId: 1, dob: "05 May 1999", extra_illness: false, on_medication: true, blood_transfusion: false, allergy: "None" },
        { time: "11:00", name: "Ahmed", age: 22, problem: "Bad Breath", gender: "Male", patientId: 2, dob: "01 August 2002", extra_illness: false, on_medication: true, blood_transfusion: false, allergy: "Ice" },
        { time: "11:30", name: "Krishna", age: 28, problem: "Jaw Pain", gender: "Male", patientId: 3, dob: "28 July 1996", extra_illness: false, on_medication: false, blood_transfusion: true, allergy: "None", blood_transfusion_date: "02/09/2024" },
        { time: "12:00", name: "Radha", age: 26, problem: "Sensitivity when biting", gender: "Female", patientId: 4, dob: "25 September 1997", extra_illness: true, on_medication: true, blood_transfusion: false, allergy: "None" },
        { time: "12:30", name: "Shahjahan", age: 24, problem: "Grinding teeth", gender: "Male", patientId: 5, dob: "15 February 2000", extra_illness: true, on_medication: false, blood_transfusion: true, allergy: "None" },
    ];

    const yesterdayPatients = [
        { time: "10:30", name: "Wafiq", age: 25, problem: "Tooth Extraction", gender: "Male", patientId: 10, dob: "05 May 1999", extra_illness: false, on_medication: true, blood_transfusion: false, allergy: "None" },
        { time: "11:00", name: "Sara", age: 22, problem: "Cavity", gender: "Female", patientId: 11, dob: "05 May 1999", extra_illness: false, on_medication: true, blood_transfusion: false, allergy: "None" },
        { time: "11:30", name: "Raj", age: 28, problem: "Gum Disease", gender: "Male", patientId: 12, dob: "1 June 1996", extra_illness: true, on_medication: false, blood_transfusion: false, allergy: "latex" },
    ];

    const tomorrowPatients = [
        { time: "10:30", name: "Rahul", age: 25, problem: "Wisdom Tooth Pain", gender: "Male", patientId: 13, dob: "05 May 1999", extra_illness: false, on_medication: true, blood_transfusion: false, allergy: "None" },
    ];

    // State management
    const [filter, setFilter] = useState("today");
    const [expandedCard, setExpandedCard] = useState(null);

    // Select patient list based on the filter
    const getFilteredPatients = () => {
        if (filter === "yesterday") return yesterdayPatients;
        if (filter === "tomorrow") return tomorrowPatients;
        return todayPatients;
    };

    // Handle filter change
    const handleFilterChange = (e) => {
        setFilter(e.target.value);
        setExpandedCard(null); // Collapse any expanded card when filter changes
    };

    // Handle card expand/collapse
    const toggleCardExpansion = (index) => {
        if (expandedCard === index) {
            setExpandedCard(null); // Collapse the card if it's already expanded
        } else {
            setExpandedCard(index); // Expand the selected card
        }
    };

    return (
        <div>
            <div className="heading">
                <h2 id="patientLabel">{filter === "today" ? "Today's Patients" : filter === "yesterday" ? "Yesterday's Patients" : "Tomorrow's Patients"}</h2>
            </div>

            <div className="filter">
                <select id="dateFilter" className="patient-filter" value={filter} onChange={handleFilterChange}>
                    <option value="today">Today</option>
                    <option value="yesterday">Yesterday</option>
                    <option value="tomorrow">Tomorrow</option>
                </select>
            </div>

            <div className="card-container">
                {getFilteredPatients().map((patient, index) => (
                    <div key={patient.patientId} className={`card ${expandedCard === index ? "expanded" : ""}`}>
                        <div className="time">{patient.time}</div>

                        <table>
                            <tbody>
                            <tr><td>Name</td><td>{patient.name}</td></tr>
                            <tr><td>Age</td><td>{patient.age}</td></tr>
                            <tr><td>Problem</td><td>{patient.problem}</td></tr>
                            {expandedCard === index && (
                                <>
                                    <tr><td>Gender</td><td>{patient.gender}</td></tr>
                                    <tr><td>Patient ID</td><td>{patient.patientId}</td></tr>
                                    <tr><td>DOB</td><td>{patient.dob}</td></tr>
                                    <tr><td>Extra Illness</td><td>{patient.extra_illness ? "Yes" : "No"}</td></tr>
                                    <tr><td>On Medication</td><td>{patient.on_medication ? "Yes" : "No"}</td></tr>
                                    <tr><td>Recent Blood Transfusion</td><td>{patient.blood_transfusion ? "Yes" : "No"}</td></tr>
                                    <tr><td>Allergy</td><td>{patient.allergy}</td></tr>
                                </>
                            )}
                            </tbody>
                        </table>

                        <div className="button-container">
                            <button className="btn" onClick={() => toggleCardExpansion(index)}>
                                {expandedCard === index ? "Back" : "Details"}
                            </button>
                        </div>
                    </div>
                ))}
            </div>
        </div>
    );
};

export default PatientModule;
